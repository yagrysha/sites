var mongoose = require('mongoose');
var passport = require('passport');
var fs = require('fs');
var path = require('path');

var File = require('models/file');
var Project = require('models/project');

module.exports.controller = function(app) {

    app.get('/home', function(req, res) {

        Project.find( {},'', {sort: "-_id"}, function( err, projects ){
            if(!err){
                res.render('home/index', {
                    projects: projects
                });
            } else{
                console.log(err);
            }
        });
    });

    app.get('/home/attach_file/:project_id', function(req, res) {

        var id = req.params.project_id;

        Project.findById( id, function(err, project){
            res.render('home/attach_file_form', {
                project: project
            });
        });
    });

    app.post('/home/attach_file/:project_id', function(req, res) {

        var uploadedFile = req.files.document;

        fs.readFile(uploadedFile.path, function (err, data) {
            var newPath = path.normalize(__dirname + '/../upload/') + uploadedFile.name;

            fs.writeFile(newPath, data, function (err) {
                if(err) {
                    console.log(err);
                } else {
                    var file = new File({
                        name: uploadedFile.name,
                        path: newPath,
                        mime: uploadedFile.type,
                        size: uploadedFile.size
                    });

                    file.save( function(err, file){
                       if(!err){

                           /*добавить добавление id файла в коллекцию проектов
                           или операций*/
                           res.redirect('/home');
                       }else {
                           console.log(err);
                       }
                    });
                };
            });
        });
    });
}