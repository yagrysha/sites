var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var Route = require('./route');
var ProjectOperations = require('./projectOperation');

var ProjectOperationStatus = require('def/projectOperationStatus');
var projectStatus = require('def/projectStatus');
var async = require('async');

var projectSchema = new Schema({
    name: { type: String, default: 'Новый проект'},
    description: { type: String, default: 'Описание проекта' },
    createdAt: {type: Date, default: Date.now },
    status: {type: Number, default: projectStatus.NEW, enum: projectStatus},
    creater_id: {type: mongoose.Schema.Types.ObjectId, ref: 'user'},
    company_id: {type: mongoose.Schema.Types.ObjectId, ref: 'company'},
    route_id: {type: mongoose.Schema.Types.ObjectId, ref: 'route'},
    curator_id: {type: mongoose.Schema.Types.ObjectId, ref: 'user'}, // manager
    // parent_id: {type: mongoose.Schema.Types.ObjectId, ref: 'project'}
});


projectSchema.static({
    findProjects: function (conditions, callback) {
        this.find(conditions)
            .exec(callback);
    },

    createdProjects: function (user, callback) {
        this.findProjects({creater_id: user._id}, callback);
    },

    supervisedProjects: function (user, callback) {
        this.findProjects({curator_id: user._id}, callback);
    }
});


projectSchema.method({
    setRoute: function (route) {
        this.route_id = route._id;
        this.save(function (err, p) {
            if (err) throw Error('cant add route');
            else
                ProjectOperations.addOperation(p, route.getFirstOperation(), function (err, projectOp) {
                    if (err) {
                        console.log('error adding first operation');
                    } else {
                        console.log('first operation: ');
                    }
                })
        })
    },
    addOperation: function (alias, cb) {
        var project = this;
        Route.findById(this.route_id, function (err, route) {
            if(err) cb(err);
            else{
                ProjectOperations.addOperation(project, route.getOperation(alias), cb)
            }
        })

/*
        async.waterfall([
            function(cb){

            }
        ], function(err, rez){});
        //Route.findby
        console.log('add operation ' + alias);
*/
    },

    setRouteByAlias: function (alias, collback) {
        var project = this;
        Route.findByAlias(alias, function (err, route) {
            if (err) {
                collback(err);
            } else {
                project.setRoute(route);
                collback(null, project);
            }
        });
    },

    createByRoute: function (params, collback) {
        this.name = params.name;
        this.description = params.description;
        this.creater_id = params.creator._id;
        this.save(function (err, project) {
            if (err) {
                collback(err);
            } else {
                project.setRouteByAlias(params.route, function (err) {
                    if (err) {
                        collback(err);
                    } else {
                        collback(null, project);
                    }
                });
            }
        });
    },

    getRoute: function (collback) {
        return Route.findById(this.route_id, collback);
    },

    setOperation: function (operaion, collback) {
        ProjectOperations.addOperation(this, operaion, collback);
    },

    setOperationByIndex: function (number, collback) {
        this.getRoute(function (err, route) {
            if (err) throw err;
            this.setOperation(route.getOperation(number), collback);
        })
    },

    getOperations: function (conditions, callback) {
        conditions.project = this._id;
        ProjectOperations.find(conditions)
            .populate('project')
            .exec(callback);
    },

    // в статусе новая и открытая
    getCurrentOperations: function (conditions, callback) {
        conditions.status = { $in: [ProjectOperationStatus.NEW, ProjectOperationStatus.OPEN] };
        this.getOperations(conditions, callback);
    },

    getOperationsCount: function (conditions, callback) {
        conditions.project_id = this._id;
        ProjectOperations.count(conditions)
            .exec(callback);
    },

    isNew: function () {
        return this.status == projectStatus.NEW;
    },
    isStatusNew: function () {
        return this.status == projectStatus.NEW;
    },
    isStatusActive: function () {
        return this.status == projectStatus.ACTIVE;
    },
    activate: function (cb) {
        this.setStatus(projectStatus.ACTIVE, cb);
    },
    close:  function (cb) {
        this.setStatus(projectStatus.ARCHIVE, cb);
    },
    setStatus: function(status, cb){
        this.status = status;
        this.save(cb);
    },
    setCurator:function(user, cb){
        this.curator_id = user._id;
        this.save(cb);
    },

    getStatusName: function () {
        return projectStatus.name[this.status];
    },

    checkAccess: function (user) {
        return (user.isAdmin() || this.creater_id == user.id || this.curator_id == user.id)
    }
});

var Project = mongoose.model('project', projectSchema);
module.exports = Project;