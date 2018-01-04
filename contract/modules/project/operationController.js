var async = require('async');

var Errors = require('main/errors').Errors;
var FieldFunctions = require('main/fieldFunctions');
var ControlFunctions = require('main/controlFunctions');

var ProjectOperations = require('models/projectOperation');

var UserTypes = require('def/userType');

var actions = {
    module: 'project',

    //форма операции
    operationAction: function () {
        async.waterfall([
            function (cb) { //init
                cb(null, actions.params.operation_id)
            },
            actions.getOperation,
            actions.checkAccess,
            actions.prepareFields
        ], this.render);
    },
    /*//Обработка введённыз данных
    doOperationAction: function () {
        async.waterfall([
            function (cb) { //init
                cb(null, actions.params.operation_id)
            },
            actions.getOperation,
            actions.checkAccess,
            actions.processFields,
            actions.saveFields,
            actions.processControls,
            actions.saveOperation
        ], this.render);
    },*/

    getOperation: function (op_id, cb) {
        ProjectOperations.findById(op_id)
            .populate('project')
            .exec(cb);
    },
    checkAccess: function (op, cb) {
        if (!op.project.isStatusNew() && !op.project.isStatusActive()) {
            return cb(Errors.NOACCESS);
            //проект в какомто нетом статусе
        }
        if (!op.access(actions.user)) {
            return cb(Errors.OPNOACCESS);
            //операция недоступна
        }
        if (op.isStatusNew()) {
            op.open(actions.user, cb);
        } else {
            cb(null, op)
        }
    },

    //Обработка введённыз данных
    doOperationAction: function () {
        async.waterfall([
            function (cb) { //init
                cb(null, actions.params.operation_id)
            },
            actions.getOperation,
            actions.checkAccess,
            actions.processFields,
            actions.saveFields,
            actions.processControls,
            actions.saveOperation
        //], this.render); //todo вернуть назад
        ], function(err, op){
            actions.res.redirect("/");
        })
    },

    //проверка введённых данных
    processFields: function (op, cb) {
        var fields = op.operation.fields;
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];

            switch (field.type) {
                case 'file':
                    field.value = actions.files[field.name];

                    if ( field.value.size == 0 ){
                        if ( field.required ){
                            return cb(Errors.FIELDREQ);
                        } else {
                            field.value = null;
                        }
                    }
                    break;
                case 'text':
                case 'select':
                    if (actions.body[field.name]) {
                        field.value = actions.body[field.name];
                    } else if (field.required) {
                        //поле обязательное
                        cb(Errors.FIELDREQ);//не введено обязательное поле
                        return ;//!!
                    } else {
                        field.value = field.settings.default ? field.settings.default : null;
                    }
                    break;

                //todo - какието ещё проверки сюда записать. напр в зависимости от типа поля
            }
        }
        cb(null, op);
    },
    //сохранение данных из формы. и вызовы операций при сохранении
    saveFields: function (op, cb) {
        var fields = op.operation.fields,
            datasetParallel = [];
        op.data = [];
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            if (field.settings.setdata && typeof FieldFunctions[field.settings.setdata] == 'function') {
                (function (field) {
                    var func = FieldFunctions[field.settings.setdata];
                    datasetParallel.push(function (callback) {
                        func(op, field, function (err, value) {
                            if (err) return callback(err);
                            field.value = value;
                            actions.pushdata(op, field);
                            callback(null, true);
                        });
                    });
                })(field);
            } else {
                actions.pushdata(op, field);
            }
        }
        if (datasetParallel.length) {
            async.parallel(datasetParallel, function (err) {
                if (err) return cb(err);
                cb(null, op);
            });
        } else {
            cb(null, op);
        }

    },

    pushdata: function (op, field) {
        var data = {
            name: field.name,
            user: actions.user
        };

        if (typeof field.value == "object"){
            var detail = {};

            if (field.type == 'file'){
                detail.type = 'file',
                detail.name = field.value.name,
                detail.id = field.value.id
            }

            data.detail = detail;
        } else {
            data.value = field.value;
        }

        op.data.push(data);

        /// todo/ потом оставить только один вариант. какой удобнее..
        /*if(!op.data2){
         op.data2={};
         }
         op.data2[field.name]=field.value;
         op.markModified('data2');*/
    },
    //обработка управляющих кнопок
    processControls: function (op, cb) {
        //todo можно не делать захардкоженых типов кнопок. а определять действие в control.doAction
        var opControls = {
            success: function () {
                op.setStatusSuccess();
                // console.log('нажато success');
            },
            error: function () {
                op.setStatusError();
                //  console.log('нажато error');
            },
            reset: function () {
                op.reset();
                // console.log('нажато reset');
            },
            save: function () {
                // console.log('нажато save');
            }
        }
        var arr = [];
        for (var cont in op.operation.controls) {
            if (actions.body[cont]) {
                opControls[cont]();
                var control = op.operation.controls[cont];
                if (control.nextOp) {
                    arr.push(function (collback) {
                        actions.addOperaions(control.nextOp, op.project, collback)
                    })
                }
                if (control.doAction) {
                    if (typeof control.doAction == 'object') {
                        control.doAction.forEach(function (act) {
                            if (typeof ControlFunctions[act] == 'function')
                                arr.push(function (collback) {
                                    ControlFunctions[act].call(actions, op, collback);
                                })
                        })
                    }else{
                        var act = control.doAction;
                        if (typeof ControlFunctions[act] == 'function')
                            arr.push(function (collback) {
                                ControlFunctions[act].call(actions, op, collback);
                            })
                    }
                }
            }
        }
        async.parallel(arr, function (err) {
            if (err) cb(err);
            else cb(null, op);
        });
    },
    addOperaions: function (nextOp, project, cb) {
        var adders = [];
        nextOp.forEach(function (alias) {
            adders.push(function (callback) {
                project.addOperation(alias, callback);
            })
        })
        async.parallel(adders, cb);
    },
    //сохранение операции
    saveOperation: function (op, cb) {
        op.save(function (err, op) {
            if (err) return cb(err);
            op._template = 'json';
            cb(null, op);
        });
    },

    //подготовка полей фломы для отрисовки
    prepareFields: function (op, cb) {
        var fields = op.operation.fields;
        var sourceParallel = [];
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            if (field.settings.datasource && typeof FieldFunctions[field.settings.datasource] == 'function') {
                (function (field) {
                    var func = FieldFunctions[field.settings.datasource];
                    sourceParallel.push(function (callback) {
                        func(function (err, values) {
                            if (err) callback(err);
                            else {
                                field.settings.value = values;
                                callback(null, true);
                            }
                        });
                    });
                })(field);
            }
        }
        if (sourceParallel.length) {
            async.parallel(sourceParallel, function (err) {
                if (err) return cb(err);
                cb(null, op);
            });
        } else {
            cb(null, op);
        }

    },


    // todo сюда по идее будут стучаться только юзеры и клиенты надо проверку доступа сделать
    operationsAction: function() {
        var user = this.user;

        async.waterfall([
            function (cb) { //init
                cb(null, user)
            },
            actions.getCurrentOperations,
        ], this.render);
    },

    getCurrentOperations: function( user, cb ){
        async.parallel(
            {
                new: function (callback) {
                    ProjectOperations.findNew( {}, user, callback );
                },
                open: function (callback) {
                    ProjectOperations.findOpen( {}, user, callback );
                }
            }, function( err, data ){
                if (err) return callback(err);

                var result = data['new'].concat(data.open);
                cb(null, {operations: result});
            }
        );
    },

    /**
     * Добавление комментария
     * */
    doCommentAction: function(){
        var operation_id = actions.params.operation_id;


        async.waterfall([
            function (cb) { //init
                cb(null, operation_id)
            },
            actions.getOperation,
            actions.checkAccess,
            actions.addComment
        ], this.render);
    },

    addComment: function( op, cb ){
        var data = actions.req.body;

        var comment = {
            user: actions.user.id,
            message: data.comment
        }

        op.comment.push(comment);
        op.save( function(err, op){
            if (err) return cb(err);

            cb(null, {_template: 'json'});
        });
    },

    /**
     * Список комментариев
     * */
    commentAction: function(){
        var operation_id = actions.params.operation_id;

        async.waterfall([
            function (cb) { //init
                cb(null, operation_id)
            },
            actions.getOperation,
            actions.checkAccess,
            actions.getComments
        ], this.render);
    },

    getComments: function(op, cb){
        var comments = op.getComments();
        console.log(op);
        cb(null, {comments: comments});
    }
};

var controller = require('main/controller')(actions);
module.exports = controller;
