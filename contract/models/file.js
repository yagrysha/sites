var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var config = require('config');
var fs = require('fs');
var async = require('async');
var crypto = require('crypto');
var http = require('http');

var fileSchema = new Schema({
    name:       { type: String },
    hashName:   { type: String },
    srv:        { type: String },
    path:       { type: String },
    mime:       { type: String },
    size:       { type: Number },
    lastAccess: { type: Date, default: Date() },
    createdAt:  { type: Date, default: Date() },
    deleted:    { type: Boolean, default: false }
});

fileSchema.static({

    /**
     * Загрузка файла
     * @param {object} file Объект загружаемого файла
     * @return {object} Объект файла
     * */
    upload: function( file, cb ){
        async.waterfall([
            function(cb){
                cb(null, file);
            },
            this.saveToDisk,
            this.saveToDatabase,
        ], cb
        );
    },

    saveToDisk: function( file, cb ){
        fs.readFile(file.path, function (err, data) {
            var hashName = File.hashName();
            var pathToFile = File.prepareDir();
            var uploadPath = pathToFile + '/' + hashName;

            fs.writeFile(uploadPath, data, function (err) {
                if(err) return cb(err);

                file.path = File.relativePath(pathToFile);
                file.hashName = hashName;
                cb(null, file);
            });
        });
    },

    saveToDatabase: function( uploadedFile, cb ){
        var file = new File({
            name: uploadedFile.name,
            hashName: uploadedFile.hashName,
            path: uploadedFile.path,
            mime: uploadedFile.type,
            size: uploadedFile.size
        });

        file.save( function(err, file){
            if (err) return cb(err);

            cb( null, file );
        });
    },

    /**
     * Получает информацию о файле, необходимую для скачивания
     * @param file_id ID Файла
     * @return {object} Информация о файле
     * */
    getFileInfo: function( file_id, cb ){
        File.findById( file_id, function(err, file){
            if (err) return cb(err);

            var path = config.upload_file_dir + '/' + file.path + '/' + file.hashName;

            var info = {
                path: path,
                name: file.name
            }

            cb(null, info);
        });
    },

    /**
     * Подготовка директории для сохранения файла
     *
     * Структура файловой системы:
     * 2014 (год)
     *      03 (месяц)
     *          21 (день)
     *
     * Если нужных директорий нету - создаются
     *
     * @return {string} Путь для сохранения файла
     */
    prepareDir: function(){
        var date = new Date();

        var year = date.getFullYear().toString();
        var month = this.addZero( date.getMonth() + 1).toString();
        var day = this.addZero( date.getDate()).toString();

        var path = config.upload_file_dir;

        path = this.createDir( path, year );
        path = this.createDir( path, month );
        path = this.createDir( path, day );

        return path;
    },

    /**
     * Создает директорию, если она не существует
     * @param {string} path Корневая директория
     * @param {string} dirname Создаваемая директория
     * @return {string} Путь к созданной директории
     */
    createDir: function(path, dirname){
        var dir = fs.readdirSync(path);
        if ( dir.indexOf( dirname ) == -1 ){
            fs.mkdirSync( path + '/' + dirname );
        }
        return path = path + '/' + dirname;
    },

    addZero: function(i) {
        return (i < 10) ? "0" + i : i;
    },

    hashName: function(){
        var salt = Math.round((new Date().valueOf() * Math.random())) + '';
        return crypto.createHash('md5').update(salt).digest('hex');
    },

    /**
     * Получает относительный путь по абсолютному
     * */
    relativePath: function( uploadPath ){
        var pathToDir = config.upload_file_dir;
        return uploadPath.substr( pathToDir.length + 1 );
    }

});

fileSchema.method({

});

var File = mongoose.model('file', fileSchema);

module.exports = File;