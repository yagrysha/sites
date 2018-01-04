var Project = require('models/project');
var ProjectOperations = require('models/projectOperation');
var ProjectOperationsData = require('models/projectOperationData').ProjectOperationData;

var async = require('async');

// заглушка
var defOperations = require("def/defOperationData");

var Errors = require('main/errors').Errors;
var error = require('main/errors').getError;

var actions = {
    module: 'log',

    projectAction: function() {
        var project_id = actions.params.project_id;
        var user = actions.user;
        async.waterfall([
            function(cb){
                cb(null, project_id, user)
            },
            actions.getProject,
            actions.checkAccessProject,
            actions.getArchiveOperations,
            actions.getOperationsData
        ], this.render );
    },
    getProject: function(project_id, user, cb){
        Project.findById( project_id, function( err, project ){
            if (err) return cb( err );
            cb( null, project, user );
        });
    },
    checkAccessProject: function (project, user, cb) {
        if (user.isAdmin() || project.creater_id == user.id || project.curator_id == user.id ){
            return cb(null, project)
        } else {
            cb( Errors.NOACCESS );
        }
    },
    getArchiveOperations: function(project, cb){
        ProjectOperations.findArchive(project._id, function(err, operations){
            if (err) return cb( err );

            cb( null, project, operations );
        })
    },
    getOperationsData: function(project, operations, cb){
        var log = ProjectOperationsData.getData( operations );
        cb(null, {project: project, log: log});
    },

    /**
     *  вызывается с помощью ajax на странице операции
     * */
    operationAction: function() {
        var operation_id = actions.params.operation_id;
        var user = actions.user;
        async.waterfall([
            function(cb){
                cb(null, operation_id, user)
            },
            actions.getOperation,
            actions.checkAccessOperation,
            actions.getRelatedOperations,
            actions.getRelatedOperationsData
        ], this.render );
    },
    getOperation: function(operation_id, user, cb){
        ProjectOperations.findById( operation_id)
            .populate('project')
            .exec( function( err, operation ){
                if (err) return cb( err );

                cb( null, operation, user );
            });
    },
    checkAccessOperation: function (operation, user, cb) {
        if ( !operation.project.isStatusNew() && !operation.project.isStatusActive() ) {
            return cb(Errors.NOACCESS);
            //проект в какомто нетом статусе
        }
        if ( !operation.access(user) ) {
            return cb(Errors.OPNOACCESS);
            //операция недоступна
        }
        cb(null, operation)
    },
    getRelatedOperations: function (operation, cb) {
        var related = operation.operation.related;

        ProjectOperations.findByAlias( related, null, function(err, related){
            if (err) return cb( err );

            var related_operations = ProjectOperations.assignedToProject( related, operation.project.id);
            cb( null, related_operations );
        })
    },
    getRelatedOperationsData: function(related_operations, cb){
        var log = ProjectOperationsData.getData( related_operations );
        cb(null, {log: log});
    },


    /**
     * Заглушка для истории операций - потом уберем
     */
    historyAction: function() {
        var project_id = actions.params.project_id;
        var user = actions.user;

        var datasetParallel = [];

        for( var i = 0; i < defOperations.length; i++ ){
            var operation = defOperations[i];
            operation.user_id = user._id;
            operation.project = project_id;
            var op_data = operation.data;

            for( var j = 0; j < op_data.length; j++ ){
                var data = op_data[j];
                data.user = user._id;
            }

            (function( operation ){
                datasetParallel.push(function (callback) {
                    ProjectOperations.create(operation, callback);
                });
            })(operation);

        }

        async.parallel(datasetParallel, this.render(null, {result: true, _template: "json"}));
    }
};

var controller = require('main/controller')(actions);
module.exports = controller;
