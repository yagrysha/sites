/**
 * проекты
 * @param app
 * @returns {Function}
 */
var log = require('./logController');


exports.routes = function(app){
    return function(){
        app.all('*?',  function(req, res, next) {
            //тут засунуть какуюнить общую проверку для модуля
            next();
        });

        app.get('/project/:project_id',  log.run('project'));
        app.get('/operation/:operation_id',  log.run('operation'));

        // заглушка для истории операций - потом уберем
        app.get('/history/:project_id',  log.run("history"));
    };
};