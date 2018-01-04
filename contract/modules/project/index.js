/**
 * проекты
 * @param app
 * @returns {Function}
 */
var project = require('./projectController');
var operation = require('./operationController');


exports.routes = function(app){
    return function(){
        app.all('*?',  function(req, res, next) {
            //тут засунуть какуюнить общую проверку для модуля
            next();
        });

        app.get('/',  project.run('index'));
        app.get('/view/:project_id',  project.run('project'));
        app.get('/operations/:project_id',  project.run('operations'));
        app.get('/create',  project.run('create'));
        app.post('/create',  project.run('create', 'saveProject'));
       // app.get('/list/admin',  project.run('adminList'));

        app.get('/list',  project.run('list'));
        app.post('/chStatus',  project.run('chStatus'));

        app.post('/operation/:operation_id',  operation.run('doOperation'));
        app.get('/operation/:operation_id',  operation.run('operation'));
        app.post('/operation/:operation_id/comment/',  operation.run('doComment'));
        app.get('/operation/:operation_id/comment/',  operation.run('comment'));
    };
};