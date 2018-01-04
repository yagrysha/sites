var config = require('config')
    , File = require('models/file')
    , async = require('async')
    , ssh2 = require('ssh2')
    , fs = require('fs')
    , ps = require('path')
    , mkdirp = require('mkdirp');

require("boot/mongoose");

//это вынести в файл конфига
config.mediaHost = 'localhost';
config.mediaPort = '2200';
config.mediaPath = '/host/projects/contract/mediasrv/';
config.mediaUser = 'yaroslav';
config.privateKey = '/home/yaroslav/.ssh/id_rsa';

//todo переписать как класс в отдельный модуль
var c = {
    connectionOptions: {
        host: config.mediaHost,
        port: config.mediaPort,
        username: config.mediaUser,
        //password: '7867'
        privateKey: require('fs').readFileSync(config.privateKey)
    },
    connection: null,
    sftp: null,
    init: function () {
        this.connection.on('ready', this.onReady);
        this.connection.on('error', this.onError);
        this.connection.on('end', this.onEnd);
        this.connection.on('close', this.onClose);
    },
    connect: function (cb) {
        //if (!(this.connection instanceof ssh2)) {
        this.connection = new ssh2();
        this.init();
        //}
        this.connection.connect(this.connectionOptions);
        if (typeof cb === 'function') {
            this.onSFTP = cb;
        }
    },
    close: function () {
        c.connection.end();
    },
    onError: function (err) {
        console.log('Connection :: error :: ' + err);
    },
    onEnd: function () {
        console.log('Connection :: end');
    },
    onClose: function () {
        console.log('Connection :: close');
    },
    onReady: function () {
        console.log('Connection :: ready');
        c.connection.sftp(function (err, sftp) {
            if (err) {
                c.onSFTP(err);
                c.onError(err);
                return;
            }
            sftp.on('end', function () {
                console.log('SFTP :: SFTP session closed');
            });
            c.sftp = sftp;
            c.onSFTP();
        })
    },
    onSFTP: function () {
        console.log('im ready');
    },
    /**
     * закачивает на север и создаёт директорю если её нет
     * @param loc
     * @param rem
     * @param cb
     */
    putFile: function (loc, rem, cb) {
        rem = config.mediaPath + rem;
        var dir = require('path').dirname(rem);
        c.createDir(dir, function (err) {
            if (err) cb(err);
            else {
                c.sftp.fastPut(loc, rem, cb);
            }
        })
    },
    /**
     * скачивает файл в локальную папку. создаёт её
     * @param rem
     * @param loca
     * @param cb
     */
    getFile: function (rem, loca, cb) {
        var dir = ps.dirname(loca),
            rem = config.mediaPath + rem;
        fs.exists(dir, function (exists) {
            if (exists) {
                c.sftp.fastGet(rem, loca, cb)
            } else {
                mkdirp(dir, function (err) {
                    if (err)  return cb(err);
                    c.sftp.fastGet(rem, loca, cb)
                });
            }
        })
    },
    readFile: function (rem) {
        return c.sftp.createReadStream(config.mediaPath + rem);
    },
    pathExist: function (path, cb) {
        c.sftp.exists(config.mediaPath + path, cb);
    },
    unlink: function (path, cb) {
        c.sftp.unlink(config.mediaPath + path, cb);
    },
    createDir: function (path, cb) {
        c.pathExist(path, function (exist) {
            if (exist)  return  cb(null);
            c.connection.exec('mkdir -p ' + path, cb);
        });
    }
}

/**
 * filemanager
 */
var filer = {
    dayLimit: 0,//39,//после стольких дней файл выкачивается на сервер
    depth: 2,//вложенность папок
    filesLimit: 10,//выкачка фалов за запуск
    localPath: '/host/projects/contract/upload/',
    /**
     * выбор из базы старх файлов
     * @param cb
     */
    findOld: function (cb) {
        var date = new Date();
        date.setDate(date.getDate() - this.dayLimit);
        var query = {"deleted": false, "srv": "", "lastAccess": {"$lte": date}}
        File.find(query).limit(filer.filesLimit).exec(cb);
    },

    /**
     * перемещает файлы на сервер
     * @param cb
     */
    moveFiles: function (cb) {
        filer.findOld(function (err, files) {
            if (err) return cb(err);
            if (files.length) {
                c.connect(function (err) {
                    if (err) return cb(err);
                    async.each(files, filer.moveFile, function (err) {
                        if (err)cb(err);
                        cb(null, files.length);
                        c.close();
                    });
                })
            } else {
                cb(null, 0);
            }
            99
        })
    },
    /**
     * отправка на сервер
     * @param file
     * @param cb
     */
    moveFile: function (file, cb) {
        var newPath = filer.genFilepath(),
            loc = filer.localPath + file.path;
        c.putFile(loc, newPath, function (err) {
            if (err) return cb(err);
            file.path = newPath;
            file.srv = c.connectionOptions.host;
            file.save(function (err, file) {
                if (err)return cb(err);
                fs.unlink(loc);
                cb(null, file);
            });
        });
    },
    genFilepath: function () {
        var crypto = require('crypto');
        var id = crypto.randomBytes(20 + filer.depth).toString('hex'),
            path = '';
        for (var i = 0; i < filer.depth; i++) {
            path += id.substr(i * 2, 2) + '/';
        }
        path += id.substr(i * 2);
        return path;
    },
    /**
     * закачка файла.
     * @param file
     * @param cb
     */
    addFile: function (file, cb) {
        fs.readFile(file.path, function (err, data) {
            if (err) return cb(err);
            var nloc = filer.genFilepath(),
                newPath = filer.localPath + nloc;
            filer.wrilteFile(newPath, data, function (err) {
                if (err) return cb(err);
                file.path = nloc;
                filer.saveToDb(file, cb);
            });

        });
    },
    /**
     * записывает файл и создаёт папку если нужно
     * @param path
     * @param data
     * @param cb
     */
    wrilteFile: function (path, data, cb) {
        var dir = ps.dirname(path);
        fs.exists(dir, function (exists) {
            if (exists) {
                fs.writeFile(path, data, cb);
            } else {
                mkdirp(dir, function (err) {
                    if (err) return cb(err);
                    fs.writeFile(path, data, cb);
                });
            }
        })
    },
    saveToDb: function (file, cb) {
        var file = new File({
            name: file.name,
            path: file.path,
            mime: file.type,
            size: file.size,
            srv: ''// для локала пусто
        });
        file.save(function (err, file) {
            if (err) return cb(err);
            cb(null, file);
        });
    },
    //
    getFile: function (id, cb) {
        File.findById(id, function (err, file) {
            if (err) return cb(err);
            if (file.srv) {
                filer.getFormSrv(file, cb);
            } else {
                file.path = filer.localPath + file.path;
                cb(null, file);
            }
        });
    },
    //скопировать файл с сервера
    getFormSrv: function (file, cb) {
        c.connect(function (err) {
            if (err) return cb(err);
            var nloc = filer.genFilepath(),
                newPath = filer.localPath + nloc,
                rem = file.path;
            c.getFile(rem, newPath, function (err) {
                if (err) return cb(err);
                file.srv = '';
                file.path = nloc;
                file.save(function (err, file) {
                    if (err)  return cb(err);
                    c.unlink(rem);
                    file.path = filer.localPath + file.path;
                    c.close();
                    cb(null, file);
                });
            })
        });
    },
    getModel: function () {
        return File;
    },
    /**
     * удаление файла
     * @param id
     * @param cb
     */
    deleteFile: function (id, cb) {
        File.findOne({_id: id, deleted: false}, function (err, file) {
            if (err) return cb(err);
            file.deleted = true;
            file.lastAccess = new Date();
            file.save(cb);
        });
    },
    /**
     * фактическое удаление файлов
     * @param cb
     * @todo можно добавить задержку. четобы удалялось через какето время
     *
     */
    clearFiles: function (cb) {
        var cond = {deleted: true} //сюда условие
        async.parallel(
            {
                local: function (cb) {
                    cond.srv = "";
                    File.find(cond, function (err, files) {
                        if (!files.length)  return cb(null, 0);
                        async.each(files
                            , function (file, cb) {
                                fs.unlink(filer.localPath + file.path, function () {
                                    file.remove(cb);
                                })
                            }
                            , function (err) {
                                if (err) return cb(err);
                                cb(null, files.length);
                            });
                    });
                },
                remote: function (cb) {
                    cond.srv = {"$ne": ""};
                    File.find(cond, function (err, files) {
                        if (!files.length)  return cb(null, 0);
                        c.connect(function (err) {
                            if (err) return cb(err);
                            async.each(files
                                , function (file, cb) {
                                    c.unlink(file.path, function (err) {
                                        file.remove(cb);
                                    });
                                }
                                , function (err) {
                                    if (err) return cb(err);
                                    cb(null, files.length);
                                    c.close();
                                });
                        })
                    });
                }
            },
            cb
        )
    }
}
module.exports = filer;

function p() {
    console.log(arguments);
}