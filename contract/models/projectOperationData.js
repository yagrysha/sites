var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var projectOperationDataSchema = new Schema({
    name: {type: String},
    value:{type: String},
    detail: {},
    updatedAt: { type: Date, default: Date.now },
    user: {type: mongoose.Schema.Types.ObjectId, ref: 'user'}
});

projectOperationDataSchema.static({
    getData: function( operations ){
        var ret = [];
        for( var i = 0; i < operations.length; i++ ){
            if ( operations[i].data ){
                var data = {
                    operation_name: operations[i].operation.name,
                    operation_id: operations[i]._id,
                    data: operations[i].data
                }
                ret.push(data);
            }
        }
        return ret;
    }
});

projectOperationDataSchema.method({

});

var ProjectOperationData = mongoose.model('projectOperationData', projectOperationDataSchema);

module.exports.projectOperationDataSchema = projectOperationDataSchema;
module.exports.ProjectOperationData = ProjectOperationData;