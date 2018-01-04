var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var userRoleSchema = new Schema({
    alias:   { type: String, unique: true },
    name:   { type: String }
});

var UserRole = mongoose.model('user_role', userRoleSchema);

module.exports = UserRole;
require('def/userRole')(UserRole);
