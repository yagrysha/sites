/**
 * проекты
 * @param app
 * @returns {Function}
 */
var project = require('modules/project/projectController');
var operation = require('modules/project/operationController');
var log = require('modules/log/logController');


exports.routes = function(app){
    return function(){
        app.all('*',  function(req, res, next) {
            //тут засунуть какуюнить общую проверку для модуля
            next();
        });

        app.get('/',  operation.run('operations'));
        app.get('/operation',  operation.run('operations'));

        app.get('/operation/log/:operation_id',  log.run('operation'));

        app.post('/operation/:operation_id',  operation.run('doOperation'));
        app.get('/operation/:operation_id',  operation.run('operation'));
    };
};