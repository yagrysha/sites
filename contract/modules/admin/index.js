/**
 * проекты
 * @param app
 * @returns {Function}
 */

var index = require('./indexController');
var user = require('./userController');
var company = require('./companyController');
var route = require('./routeController');

exports.routes = function(app){
    return function(){
        app.all('*', index.run('access'));

        app.get('/',  index.run('index'));

        app.get('/users',  user.run('list'));
        app.get('/users/new',  user.run('create'));
        app.post('/users/new',  user.run('create', true));
        app.get('/users/edit/:id',  user.run('edit'));
        app.post('/users/edit/:id',  user.run('edit', true));
        app.get('/users/delete/:id',  user.run('delete'));

        app.get('/company',  company.run('list'));
        app.get('/company/new',  company.run('create'));
        app.post('/company/new',  company.run('create', true));
        app.get('/company/edit/:id',  company.run('edit'));
        app.post('/company/edit/:id',  company.run('edit', true));
        app.get('/company/delete/:id',  company.run('delete'));

        app.get('/route',  route.run('list'));
        app.get('/route/edit/:route_id',  route.run('edit'));
        app.get('/route/createfield/:route_id', route.run('fieldCreate'));
        app.post('/route/createfield/:route_id', route.run('doFieldCreate'));
        app.get('/route/createoperation/:route_id', route.run('operationCreate'));
        app.post('/route/createoperation/:route_id', route.run('doOperationCreate'));
        app.get('/route/field/edit/:field_id', route.run('fieldEdit'));
        app.post('/route/field/edit/:field_id', route.run('doFieldEdit'));
        app.get('/route/operation/edit/:operation_id', route.run('operationEdit'));
        app.post('/route/operation/edit/:operation_id', route.run('doOperationEdit'));

    };
};