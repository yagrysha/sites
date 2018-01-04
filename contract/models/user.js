var mongoose = require('mongoose');
var crypto = require('crypto');
var Schema = mongoose.Schema;

var UserRole = require('models/userRole');

var UserTypes = require('def/userType');


var userSchema = new Schema({
    username: { type: String, unique: true },
    password: { type: String },
    email: { type: String, unique: true },
    type: {type: Number, default: UserTypes.NOBODY},
    roles: [
        { type: mongoose.Schema.Types.ObjectId, ref: 'user_role' }
    ],
    salt: { type: String },
    settings: {
        sendNotifyEmail: { type: Boolean }
    }
});

userSchema.path("password", {
    set:function(pass){
        this.salt = this.makeSalt();
        return this.createHash(pass + this.salt);
    }
})

userSchema.method({
   addRole: function (role, collback) {
       if (this.roles.indexOf(role._id) == -1) {
           this.roles.push(role._id);
           if (collback)this.save(collback);
       }
   },

   isAdmin: function () {
       return (this.type == UserTypes.ADMIN);
   },

   isClient: function() {
       return (this.type == UserTypes.CLIENT);
   },

   canCrateProject: function () {
       if ( this.isAdmin() || this.isClient() ) return true;
       //todo проверку по типа и (или) ролям
   },

   canOpenOperation: function (op) {
       for (var i = 0; i < this.roles.length; i++) {
           if (op.access.indexOf(this.roles[i].alias) != -1) {
               return true;
           }
       }
       return false;
   },

   makeSalt: function(){
       return Math.round((new Date().valueOf() * Math.random())) + '';
   },

   createHash: function(text){
       return crypto.createHash('md5').update(text).digest('hex');
   },

    authenticate: function(pass) {
        var password = this.password.toString();
        password = password.substring(1, password.length - 1);

        return this.createHash( pass + this.salt ) === password;
    },

    /*
    * метод для удобства обновления через .save()
    * стандартный .update() не позволяет ипользовать middleware
    * */
    updateOne: function( newObject, callback ){
        for (property in newObject){
            this[property] = newObject[property];
        }

        this.save(callback);
    }
});

var User = mongoose.model('user', userSchema);

module.exports = User;
require('def/user')(User, UserRole);

