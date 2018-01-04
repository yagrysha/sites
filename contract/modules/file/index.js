/**
 * проекты
 * @param app
 * @returns {Function}
 */
var file = require('./fileController');


exports.routes = function(app){
    return function(){
        app.all('*?',  function(req, res, next) {
            //тут засунуть какуюнить общую проверку для модуля
            next();
        });

        app.get('/log/:operation_id/:file_id',  file.run('log'));
    };
};