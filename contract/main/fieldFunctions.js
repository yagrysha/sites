var Company = require('models/company');
var File = require('models/file');

var fs = require('fs');

module.exports = {
    getcompanies: function (cb) {
        Company.find({}, cb);
    },
    setcompanies: function (op, field, cb) {
        op.project.company_id = field.value;
        //todo проверить есть ли такая компания.
        op.project.save(function (err, project) {
            if (err) {
                cb(err);
            } else {
                cb(null, field.value);
            }
        });
    },

    uploadfile: function(op, field, cb){
        if ( field.value ){
            var uploadedFile = field.value;
            File.upload( uploadedFile, function(err, file){
                if (err) return cb(err);

                cb(null, file);
            })
        } else {
            cb( null, null );
        }
    }
}
