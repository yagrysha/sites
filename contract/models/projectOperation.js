var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var async = require('async');

var projectOperationDataSchema = require('./projectOperationData').projectOperationDataSchema;

var projectOperationStatus = require('def/projectOperationStatus');


var projectOperationSchema = new Schema({
   // parent: {type: mongoose.Schema.Types.ObjectId, ref: 'ProjectOperation'},
    project: {type: mongoose.Schema.Types.ObjectId, ref: 'project'},
    operation: {},//прямо сюда копируется операция из роута
    user_id: {type: mongoose.Schema.Types.ObjectId, ref: 'user'},
    createdAt: { type: Date, default: Date.now },
    startTime: { type: Date},
    endTime: { type: Date},
    status: {type: Number, default: projectOperationStatus.NEW, enum:projectOperationStatus},
    data: [projectOperationDataSchema],//данные по этой операции,
    comment: [{
        user: {type: mongoose.Schema.Types.ObjectId, ref: 'user'},
        date: {type: Date, default: Date()},
        message: {type: String}
    }]
});

projectOperationSchema.static({
    addOperation: function (project, operation, callback) {
        return this.create({
            project: project._id,
            operation: operation
        }, callback);
    },

    findOperations: function(conditions, callback){
        this.find(conditions)
            //.populate('operation_id')
            .populate('project')
            .exec(callback);
    },

    findNew: function(conditions, user, callback){
        conditions.status = projectOperationStatus.NEW;
        this.findOperations( conditions, function(err, operations){
            if (err) return callback(err);

            ProjectOperation.checkAccess( operations, user, callback );
        })
    },

    findOpen: function(conditions, user, callback){
        conditions.status = projectOperationStatus.OPEN;
        conditions.user_id = user._id;
        return this.findOperations(conditions, callback)
    },

    /**
     * Ищет операции по алиасу
     * @param {string, array} alias Алиас или массив алиасов
     * @param {object, null} user Если существует, то поиск с проверкой доступа
     * @return {array} Список операций
     */
    findByAlias: function( alias, user, callback ){
        var condition = {};
        if ( typeof(alias) == "string" ){
            condition = { 'operation.alias': alias };
        } else {
            condition = { 'operation.alias': { $in : alias } }
        }

        this.findOperations( condition, function( err, operations ){
            if ( err ) return callback( err );

            if (user){
                ProjectOperation.checkAccess( operations, user, callback );
            } else {
                callback( null, operations );
            }

        });
    },

    // операции всех статусов кроме новая
    findArchive: function( project_id, callback ){
        this.findOperations( {
            status: { $ne: projectOperationStatus.NEW },
            project: project_id
        }, callback);
    },

    checkAccess: function( operations, user, callback ){
        var ret = [];
        for(var i = 0; i < operations.length; i++){
            var op = operations[i];
            if( op.access(user) ){
                ret.push(op);
            }
        }
        callback(null, ret);
    },

    /**
     * Получает из списка операций те, которые привязаны к конкретному проекту
     * @param {array} operations Список операций
     * @param {string} project_id ID проверяемого проекта
     * @return {array} Список привязанных к проекту операций
     */
    assignedToProject: function( operations, project_id ){
        var ret = [];
        for(var i = 0; i < operations.length; i++){
            var op = operations[i];
            if ( op.project.id == project_id ){
                ret.push(op);
            }
        }
        return ret;
    },

    /**
     * Получает список операций, с которыми связана текущая операция проекта
     * @param {string} alias Текущая операция
     * @param {string} project_id Текущий проект
     * @return {array} Список операций
     * */
    findAssociated: function(alias, project_id, cb){
        var conditions = {
            project: project_id,
            'operation.related': alias
        }
        this.findOperations( conditions, cb);
    }
});

projectOperationSchema.method({
    isNew: function(){
        //интересная фигня. почемуто такой метод не создаётся
        return (this.status==projectOperationStatus.NEW);
    },
    //поэтому сделал этот
    isStatusNew: function(){
        return (this.status==projectOperationStatus.NEW);
    },
    isOpen: function(){
        return (this.status==projectOperationStatus.OPEN);
    },
    isError: function(){
        return (this.status==projectOperationStatus.ERROR);
    },
    // добавил еще статус ошибка
    isUserOpen: function(user){
        return (this.isOpen() && !this.isError() && this.user_id==user.id);
    },
    access:function(user){
        if(this.isUserOpen(user)) {
            return true;
        }
        if(this.isStatusNew()){
            if ( user.canOpenOperation( this.operation ) ) return true
        }
        return false;
    },
    open: function(user, cb){
        this.user_id=user._id;
        this.startTime=Date.now();
        this.status=projectOperationStatus.OPEN;

        this.save(function(err, op){
            if (err) return cb(err);

            cb(null, op);
        });
    },
    reset: function(cb){
        //тут может быть не 0 а чтото дургое нада ставить.
        this.user_id=null;
        this.data=[];
        this.startTime=0;
        this.endTime=0;
        this.status=projectOperationStatus.NEW;
        //this.save(cb);
    },
    setStatusSuccess:function(){
        this.close(projectOperationStatus.SUCCESS);
    },
    setStatusError:function(){
        this.close(projectOperationStatus.ERROR);
    },
    close: function(status){
        this.endTime=Date.now();
        this.status=status;
       // this.save(cb);
    },
    getStatusName: function () {
        return projectOperationStatus.name[this.status];
    },
    getComments: function(){
        return this.comment;
    }

});


var ProjectOperation = mongoose.model('projectOperation', projectOperationSchema);
module.exports = ProjectOperation;