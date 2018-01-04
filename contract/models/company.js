var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var CompanySchema = new Schema({
    name:   { type: String }
});

CompanySchema.static({

});

CompanySchema.method({

});

var Company = mongoose.model('company', CompanySchema);

module.exports = Company;