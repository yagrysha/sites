var async = require('async');

var Errors = require('main/errors').Errors;

var Route = require('models/route');
var UserRole = require('models/userRole');

var actions = {
    module: 'route',

    // список маршрутов
    listAction: function () {
        Route.find({}, function(err, routes){
            actions.render(err, {routes: routes});
        })
    },

    editAction: function() {
        var route_id = actions.params.route_id

        async.parallel(
            {
                route: function(cb) {
                    Route.getRoute( route_id, cb )
                },
                operations: function(cb) {
                    Route.getOperationsByRouteID( route_id, cb );
                },
                op_fields: function(cb) {
                    Route.getOperationsWithFields( route_id, cb );
                }
            }, function(err, data){
                //todo ошибки
                actions.render(err, data);
            }
        );
    },

    fieldEditAction: function(){
        var field_id = actions.params.field_id;

        Route.getField(field_id, function(err, field){
            var data = {
                _template: "modal/field_edit",
                data: field
            }

            actions.render( null, data);
        })
    },

    doFieldEditAction: function(){
        var field_id = actions.params.field_id;
        var data = actions.req.body;

        async.waterfall([
            function(cb) {
                cb(null, field_id, data);
            },
            actions.editFieldValidation,
            function(field_id, data, cb){

                Route.getRouteWithFieldID(field_id, function(err, route){
                    var field = route.getFieldByID( field_id );

                    field.attr = '';
                    field.settings = '';
                    field.required = '';
                    for( property in data) {
                        field[property] = data[property];
                    }

                    route.save( actions.ajaxResponse );
                })
            }
        ]);
    },

    editFieldValidation: function(field_id, data, cb){
        async.waterfall([
            function(cb) {
                cb(null, field_id, data);
            },
            // загружаем родительскую операцию
            function(field_id, data, cb){
                Route.getRouteWithFieldID(field_id, function(err, route){
                    var operation = route.getOperationByFieldID( field_id );
                    cb(null, operation.id, field_id, data);
                })
            },
            function(operation_id, field_id, data, cb){
                // Есть ли дубли идентификаторов в операции
                if (data.name){
                    Route.getFieldsByOperationID( operation_id, function(err, fields){
                        for ( var i = 0; i < fields.length; i++ ){
                            if ( fields[i].name == data.name && fields[i].id != field_id ){
                                return actions.ajaxResponse(Errors.FIELDDOUBLE);
                            }
                        }
                        cb(null, field_id, data);
                    })
                } else {
                    return actions.ajaxResponse(Errors.NOFIELDNAME);
                }
            }
        ], cb);
    },


    fieldCreateAction: function(){
        var route_id = actions.params.route_id;

        Route.getOperationsByRouteID(route_id, function(err, operations){
            var data = {
                _template: "modal/field_create",
                operations: operations
            }

            actions.render( null, data);
        })
    },

    doFieldCreateAction: function(){
        var data = actions.req.body;

        async.waterfall([
            function(cb) {
                cb(null, data)
            },
            actions.createFieldValidation,
            function(data, cb){
                Route.getRouteWithOperationID(data.operation_id, function(err, route){
                    var operation = route.getOperationByID( data.operation_id );

                    var field = {}
                    delete data.operation_id;
                    for( property in data) {
                        field[property] = data[property];
                    }
                    operation.fields.push(field);

                    route.save( actions.ajaxResponse );
                })
            }
        ]);
    },

    createFieldValidation: function(data, cb){
        // Выбрана ли операция
        if ( ! data.operation_id ){
            return actions.ajaxResponse(Errors.OPNOCHECK);
        }

        // Есть ли дубли идентификаторов в операции
        if (data.name){
            Route.getFieldsByOperationID( data.operation_id, function(err, fields){
                for ( var i = 0; i < fields.length; i++ ){
                    if ( fields[i].name == data.name ){
                        return actions.ajaxResponse(Errors.FIELDDOUBLE);
                    }
                }
                cb(null, data);
            })
        } else {
            return actions.ajaxResponse(Errors.NOFIELDNAME);
        }
    },

    operationEditAction: function(){
        var operation_id = actions.params.operation_id;

        async.parallel({
            roles: function(cb){
                UserRole.find( {}, cb);
            },
            data: function(cb){
                Route.getOperation(operation_id, cb );
            },
            operations: function(cb){
                Route.getRouteWithOperationID( operation_id, function(err, route){
                    var operations = route.getOperations();
                    cb(null, operations);
                });
            }
        }, function( err, data ){
            data._template = "modal/operation_edit";

            actions.render( err, data);
        });
    },

    doOperationEditAction: function(){
        var operation_id = actions.params.operation_id;
        var data = actions.req.body;

        async.waterfall([
            function(cb) {
                cb(null, operation_id, data)
            },
            actions.editOperationValidation,
            function(operation_id, data, cb){
                Route.getRouteWithOperationID(operation_id, function(err, route){
                    var operation = route.getOperationByID( operation_id );

                    operation.access = null;
                    operation.related = null;
                    for( property in data) {
                        operation[property] = data[property];
                    }

                    route.save( actions.ajaxResponse );
                })
            }
        ]);
    },

    editOperationValidation: function(operation_id, data, cb){
        async.waterfall([
            function(cb) {
                cb(null, operation_id, data);
            },
            // загружаем родительский роут
            function(operation_id, data, cb){
                Route.getRouteWithOperationID(operation_id, function(err, route){
                    cb(null, route.id, operation_id, data);
                })
            },
            function(route_id, operation_id, data, cb){
                // Есть ли дубли алиасов в роуте
                if (data.alias){
                    Route.getOperationsByRouteID( route_id, function(err, operations){
                        for ( var i = 0; i < operations.length; i++ ){
                            if ( operations[i].alias == data.alias && operations[i].id != operation_id ){
                                return actions.ajaxResponse(Errors.OPDOUBLE);
                            }
                        }
                        cb(null, operation_id, data);
                    })
                } else {
                    return actions.ajaxResponse(Errors.NOOPALIAS);
                }
            }
        ], cb);
    },

    operationCreateAction: function(){
        var route_id = actions.params.route_id;

        async.parallel({
            roles: function(cb){
                UserRole.find( {}, cb);
            },
            operations: function(cb){
                Route.getOperationsByRouteID( route_id, cb);
            }
        }, function( err, data ){
            data._template = "modal/operation_create";

            actions.render( err, data);
        });
    },

    doOperationCreateAction: function(){
        var route_id = actions.params.route_id;
        var data = actions.req.body;

        async.waterfall([
            function(cb) {
                cb(null, route_id, data)
            },
            actions.createOperationValidation,
            function(route_id, data, cb){
                Route.getRoute(route_id, function(err, route){
                    var operation = {};
                    for( property in data) {
                        operation[property] = data[property];
                    }
                    route.operations.push(operation);

                    route.save( actions.ajaxResponse );
                })
            }
        ]);
    },

    createOperationValidation: function(route_id, data, cb){
        // Есть ли дубли алиасов в роуте
        if (data.alias){
            Route.getOperationsByRouteID( route_id, function(err, operations){
                for ( var i = 0; i < operations.length; i++ ){
                    if ( operations[i].alias == data.alias ){
                        return actions.ajaxResponse(Errors.OPDOUBLE);
                    }
                }
                cb(null, route_id, data);
            })
        } else {
            return actions.ajaxResponse(Errors.NOOPALIAS);
        }
    },

    ajaxResponse: function(err){
        var response = {
            _template: "json"
        };

        if (err) {
            response.status = "fail",
            response.message = err.toString();
        } else {
            response.status = "ok"
        }

        actions.render(null, response);
    }
};

var controller = require('main/controller')(actions);
module.exports = controller;