var File = require('models/file');
var ProjectOperations = require('models/projectOperation');

var async = require('async');

var Errors = require('main/errors').Errors;
var error = require('main/errors').getError;

var actions = {
    module: 'file',

    logAction: function() {
        var operation_id = actions.params.operation_id;
        var file_id = actions.params.file_id;
        var user = actions.user;

        async.waterfall([
            function(cb){
                cb(null, operation_id, file_id, user)
            },
            actions.getOperation,
            actions.checkAccessOperation,
            actions.download
        ], actions.render);
    },

    getOperation: function(operation_id, file_id, user, cb){
        ProjectOperations.findById( operation_id)
            .populate('project')
            .exec( function( err, operation ){
                if (err) return cb( err );

                cb( null, operation, file_id, user );
            });
    },

    checkAccessOperation: function (operation, file_id, user, cb) {
        var access = true;

        if (user.isAdmin() || operation.project.creater_id == user.id || operation.project.curator_id == user.id ){
        } else {
            access = false;
        }

        if ( operation.access(user) ) {
        } else {
            access = false;
        }

        if (access == false ){
            var alias = operation.operation.alias;
            var project_id = operation.project.id;

            async.waterfall([
                function(cb){
                    ProjectOperations.findAssociated(alias, project_id, cb);
                },
                function( operations, cb){
                    ProjectOperations.checkAccess(operations, user, cb);
                }
            ], function(err, ops){
                if (err) return cb(err);

                if (ops.length)
                    return cb(null, file_id);
                else
                    return cb(Errors.FILENOACCESS);
            });
        } else {
            return cb(null, file_id)
        }
    },

    download: function (file_id, cb) {
        File.getFileInfo(file_id, function(err, info){
            if (err) return cb( err );

            actions.res.download(info.path, info.name, function(err){
                if (err) {
                    return cb( err );
                } else {
                    cb(null, {_template: 'json'});
                }
            });
        });
    }
};

var controller = require('main/controller')(actions);
module.exports = controller;
