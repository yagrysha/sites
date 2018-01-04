var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var operationFieldSchema = new Schema({
    title: {type: String, required: true}, //будет отображаться в логах
    name:{ type: String, required: true},
    label:{ type: String, required: true},
    description:{ type: String},
    type:{type: String, required: true}, //тоже потом сделать схемой
    required: {type:Boolean, default: false},
    settings:{},
    attr:{}
});

/*var operationControlSchema = new Schema({
    name: {type: String},
    title:{type: String},
    description:{type: String},
    nextOp: [Number]//следующие операции
});*/

var operationSchema = new Schema({
    alias: { type: String, required: true},
    name:  { type: String, required: true},
    description:   { type: String},
    related:[String],
    access: {type:Array, required: true},
    fields:[operationFieldSchema],
    controls:{}//[operationControlSchema]
});

var routeSchema = new Schema({
    alias: { type: String, unique: true},
    name:  { type: String},
    description:   { type: String},
    operations: [operationSchema]
});

routeSchema.static({
    findByAlias: function (name, callback) {
        return this.findOne({ alias: name }, callback);
    },

    getRoutes: function( cb ){
        Route.find( {}, function( err, routes ){
            if (err) return cb(err);

            if ( routes ){
                cb(null, routes)
            } else {
                cb(null, null);
            }
        });
    },

    getRoute: function( route_id, cb ){
        Route.findById( route_id, function(err, route){
            if (err) return cb (err);

            if (route){
                cb(null, route);
            } else {
                cb(null, null);
            }
        })
    },

    getRouteWithFieldID: function( field_id, cb ){
        Route.findOne( {"operations.fields._id": field_id}, function(err, route){
            if (err) return cb (err);

            if (route){
                cb(null, route);
            } else {
                cb(null, null);
            }
        })
    },

    getRouteWithOperationID: function( operation_id, cb ){
        Route.findOne( {"operations._id": operation_id}, function(err, route){
            if (err) return cb (err);

            if (route){
                cb(null, route);
            } else {
                cb(null, null);
            }
        })
    },

    getOperations: function( cb ){
        Route.find( {}, function(err, routes){
            if (err) return cb(err);

            var operations = [];
            if (routes){
                for( var i = 0; i < routes.length; i++ ){
                    for( var j = 0; j < routes[i].operations.length; j++ ){
                        operations.push(routes[i].operations[j]);
                    }
                }

            }
            cb(null, operations);
        })
    },

    getOperationsByRouteID: function( route_id, cb ){
        Route.findById( route_id, function(err, route){
            if (err) return cb(err);

            var operations = [];
            if (route){
                for( var i = 0; i < route.operations.length; i++ ){
                    operations.push(route.operations[i]);
                }
            }
            cb(null, operations);
        })
    },

    getOperationsWithFields: function( route_id, cb ){
        Route.getOperationsByRouteID( route_id, function(err, operations){
            if (err) return cb(err);

            var operations_fields = [];

            for ( var i = 0; i < operations.length; i++ ){
                if ( operations[i].fields.length ){
                    operations_fields.push(operations[i]);
                }
            }

            cb(null, operations_fields);
        })
    },

    getOperation: function( operation_id, cb ){
        Route.findOne( { "operations._id": operation_id }, function(err, route){
            if (err) return cb(err);

            var operation = null;
            if (route){
                for( var i = 0; i < route.operations.length; i++ ){
                    if ( route.operations[i].id == operation_id ){
                        operation = route.operations[i];
                    }
                }
            }
            cb(null, operation);
        })
    },

    getFields: function( cb ){
        Route.getOperations( function(err, operations){
            if (err) return cb(err);

            var fields = [];
            if (operations){
                for( var i = 0; i < operations.length; i++ ){
                    for ( var j = 0; j < operations[i].fields.length; j++ ){
                        fields.push(operations[i].fields[j])
                    }
                }

            }
            cb(null, fields);
        })
    },

    getFieldsByRouteID: function( route_id, cb ){
        Route.getOperationsByRouteID( route_id, function(err, operations){
            if (err) return cb(err);

            var fields = [];
            for (var i = 0; i < operations.length; i++){
                for( var j = 0; j < operations[i].fields.length; j++ ){
                    fields.push(operations[i].fields[j]);
                }
            }
            cb(null, fields);
        })
    },

    getFieldsByOperationID: function( operation_id, cb ){
        Route.getOperation( operation_id, function(err, operation){
            if (err) return cb(err);

            var fields = [];
            if (operation){
                for ( var i = 0; i < operation.fields.length; i++ ){
                    fields.push( operation.fields[i] );
                }
            }
            cb(null, fields);
        })
    },

    getField: function( field_id, cb ){
        Route.getFields( function( err, fields ){
            if (err) return cb(err);

            var field = null;
            for (var i = 0; i < fields.length; i++){
                if ( fields[i].id == field_id ){
                    field = fields[i];
                }
            }
            cb(null, field);
        })
    }
});

routeSchema.method({
    getOperation: function (index) {
        if(typeof index=='number'){
            return this.operations[index];
        }else if(typeof index=='string'){
            for(var i = 0 ; i<this.operations.length; i++){
                if(this.operations[i].alias==index){
                    return this.operations[i]
                }
            }
            //по алиасу
        }
        return null;
    },

    getFirstOperation: function () {
        return this.getOperation(0);
    },

    getOperations: function(){
        var operations = [];
        for ( var i = 0; i < this.operations.length; i++ ){
            operations.push(this.operations[i]);
        }
        return operations;
    },

    getOperationByID: function( operation_id ){
        var operation = null;

        var operations = this.getOperations();
        for ( var i = 0; i <operations.length; i++ ){
            if (operations[i].id == operation_id){
                operation = operations[i];
            }
        }
        return operation;
    },

    getFields: function(){
        var fields = [];

        var operations = this.getOperations();
        for ( var i = 0; i <operations.length; i++ ){
            for (var j = 0; j < operations[i].fields.length; j++){
                fields.push(operations[i].fields[j]);
            }
        }
        return fields;
    },

    getFieldByID: function( field_id ){
        var field = null;

        var fields = this.getFields();
        for ( var i = 0; i <fields.length; i++ ){
            if (fields[i].id == field_id){
                field = fields[i];
            }
        }
        return field;
    },

    getOperationByFieldID: function( field_id ){
        var operation = null;

        var operations = this.getOperations();
        for ( var i = 0; i <operations.length; i++ ){
            for( var j = 0; j < operations[i].fields.length; j++ ){
                if ( operations[i].fields[j].id == field_id ){
                    operation = operations[i];
                }
            }
        }
        return operation;
    }
});

var Route = mongoose.model('route', routeSchema);

module.exports = Route;
require('def/defRoute')(Route);
