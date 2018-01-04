/**
 * пример использования менеджера файлов
 * @type {exports}
 */
var util = require('util');
var express = require('express');
var fm = require('filer');
var app = express();
app.use(express.bodyParser());


app.get('/', function(req, res){
    fm.getModel().find({deleted:false},function(err, files){
        if(err) throw Error(err);
        res.render('index', { files: files });
    });
});

app.get('/move', function(req, res){
    fm.moveFiles(function(){
        res.json({res:arguments});
    });
});
app.get('/clear', function(req, res){
    fm.clearFiles(function(){
        res.json({res:arguments});
    });
});
app.post('/upload', function(req, res){
    fm.addFile(req.files.file, function(err, file){
        if(err){
            res.json({status:"Error", error: err, u:util.inspect(file)});
        }else{
            res.json({status:"OK", file: {
                name: file.name,
                id: file.id,
                size: file.size
            }});
        }
    })
});
app.post('/delete', function(req, res){
    fm.deleteFile(req.body.id, function(err){
        if(err)res.json({error:err});
        else res.json({status:"OK"});
    });
});

app.get('/download/:id', function(req, res){
    fm.getFile(req.params.id, function(err, file){
        res.download(file.path, file.name);
    });
});

app.set('views', __dirname + '/views/fapp');
app.set('view engine', 'jade');
app.use(express.static(__dirname+ '/public'));

var server = app.listen(3000, function() {
    console.log('Listening on port %d', server.address().port);
});
function p(){
    console.log(arguments);
}