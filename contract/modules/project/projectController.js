var Project = require('models/project');
var ProjectOperations = require('models/projectOperation');
var User = require('models/user')

var UserTypes = require('def/userType');

var async = require('async');


var Errors = require('main/errors').Errors;
var error = require('main/errors').getError;


var actions = {
    module: 'project',

    indexAction: function () {
        var user = this.user;

        switch (this.user.type){
            // для админа по коду я ничего не менял, чтобы нам удобнее было работать
            case UserTypes.ADMIN:
                async.parallel(
                    {
                        canCreate: function (callback) {
                            callback(null, user.canCrateProject());
                        },
                        myProjects: function (callback) {
                            Project.createdProjects(user, callback)
                        },
                        sProjects: function (callback) {
                            Project.supervisedProjects(user, callback)
                        },
                        newOperations:function(cb){
                            ProjectOperations.findNew({}, user, cb);
                        },
                        openOperations:function(cb){
                            ProjectOperations.findOpen({}, user, cb);
                        },
                        aliasOperations:function(cb){
                            ProjectOperations.findByAlias('settest', user, cb);
                        }
                    }, actions.render
                );
                break;

            case UserTypes.MANAGER:
                Project.supervisedProjects(user, function(err, projects){
                    actions.render(err, {projects: projects})
                });
                break

            case UserTypes.USER:
                // у юзера по сути нету проектов у него только операции
                // пока что возвращаю null
                actions.render(null, null)
                break;

            case UserTypes.CLIENT:
                Project.createdProjects(user, function(err, projects){
                    actions.render(err, {projects: projects})
                });
                break;
        }

    },
    projectAction: function () {
        var project_id = this.params.project_id;
        var user = this.user;
        async.parallel(
            {
                project: function (callback) {
                    actions.getProject(project_id, user, callback);
                }
                // тут думал сделать ещё выборку опреаций.
                // operations: f
                // но можно это сдлеать доп ajax запросом из клиента
                // см. пример operationsAction
            },
            actions.renderResult);
    },

    operationsAction: function() {
        var project_id = this.params.project_id;
        var user = this.user;
        // по идее лишняя проверка
        //if(project_id){
            async.waterfall([
                function(callback){
                    actions.getProject(project_id, user, callback);
                },
                function(project, cb){
                    actions.getCurrentOperations(project, cb)
                },
                function(operations, callback){
                    callback(null, {_template:'json', items:operations});
                }
            ], actions.renderResult);
        //} else{
        //    ProjectOperations.findNew({}, this.user, actions.renderResult);
        //}
    },

    //для разных юзеров(случаев) разные
    getCurrentOperations:function(project, callback){
        switch ( this.user.type ){
            case UserTypes.ADMIN:
            case UserTypes.MANAGER:
                project.getCurrentOperations({}, callback);
                break;
            case UserTypes.CLIENT:
                async.parallel(
                    {
                        new: function (callback) {
                            ProjectOperations.findNew( { project: project._id }, actions.user, callback );
                        },
                        open: function (callback) {
                            ProjectOperations.findOpen( { project: project._id }, actions.user, callback );
                        }
                    }, function( err, data ){
                        if (err) return callback(err);

                        var result = data.new.concat(data.open);
                        callback(null, result);
                    }
                );
        }

            //Доступнаые для юзера
           // ProjectOperations.findNew({}, this.user, callback);
            //открытые юзером
            //ProjectOperations.findOpen({}, this.user, callback);
    },

    getProject:function(project_id, user, callback){
            Project.findById(project_id, function (err, project) {
                if (err) {
                    callback(error(Errors.PROJECTNOTFOUND, err));
                } else {
                    if (project && project.checkAccess(user)) {
                        callback(null, project);
                    } else {
                        callback(error(Errors.NOACCESS, err));
                    }
                }
            });
    },
    renderResult:function (err, result) {
        if (err) {
            controller.renderView(err, actions.res);
        } else {
            controller.renderView(result, actions.res, result.json);
        }
    },

    newOperationsAction: function () {
        ProjectOperations.findNew(function (err, ops) {

        })
    },


    /**
     * создание заказ. каr пример одной функции для разных роутов с одним шаблоном view
     * @param doСreate
     * @returns {*}
     */
    createAction: function (doСreate) {
        if ( !this.user.canCrateProject() ) return error(Errors.NOACCESS);
        var project = new Project();
        if (doСreate) {
            return this.doCreate(project);
        } else {
            return {
                project: project
            }
        }
    },

    doCreate: function (project) {
        var form = this.req.body;
        project.createByRoute({
            name: form.name,
            description: form.desc,
            creator: this.user,
            route: 'document'
        //}, actions.render); //todo вернуть на место
        }, function(err, project){
            actions.res.redirect('/')
        });
    },


    /**
     * список проектов.
     *  для админа - все
     *  для куратора - его и новые без куратора
     *  для юзера - в которых участвует
     *  для клиента к- которые создал
     * @returns {{mes: string}}
     */
    listAction: function () {
        switch (this.user.type) {
            case UserTypes.ADMIN:
                this.allProjects();
                break;
            case UserTypes.MANAGER:
                this.supervisedProjects();
                break;
            case UserTypes.USER:
                this.myProjects();
                break;
            default :
                actions.render('ошибка');
        }
    },

    adminListAction: function () {
        this.allProjects();
    },

    chStatusAction: function () {
        if (!this.user.isAdmin()) {
            actions.render(Errors.NOACCESS);
        }
        Project.findByIdAndUpdate(this.body.id, { $set: { status: this.body.status }}, function (err) {
            if (err) {
                actions.render(error(Errors.PROJECTNOTFOUND, err));
            } else {
                actions.render(null, {success: true});
            }
        })
    },

    //список проектов для их создателя
    myProjects: function () {
        this.getProjects({creater_id: this.user._id});
    },
    //проекты куратора
    supervisedProjects: function () {
        this.getProjects({curator_id: this.user._id});
    },
    //все проекты для админа
    allProjects: function () {
        this.getProjects({});
    },
    getProjects: function (condition) {
        Project.find(condition, actions.render);
    }
};

var controller = require('main/controller')(actions);
module.exports = controller;
