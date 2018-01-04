
/**
 * Module dependencies.
 */
var express = require('express');
var http = require('http');
var path = require('path');
var fs = require('fs');
var namespace = require('express-namespace');

var app = express();

/*
 Инициализация библиотек
   @Express
   @Mongoose
   @Passport
*/
require('boot/index.js')(app);

/*
 Подключение роутов
 Если диретория - то через namespace
 */
fs.readdirSync('./modules').forEach(function (module) {
    var modPath = path.join('modules', module);

    if ( fs.statSync(modPath).isDirectory() ){
        app.namespace( '/'+module, require(modPath).routes(app) );
    } else {
        var route = require('./modules/' + module)
        route.controller(app);
    }
});

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
