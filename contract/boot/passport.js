var Users = require('models/user');

var passport       = require('passport');
var LocalStrategy  = require('passport-local').Strategy;

passport.use('local', new LocalStrategy(
  {
    usernameField: 'username',
    passwordField: 'password'
  },
  function(username, password, done) {
    Users.findOne({ username: username }).populate('roles').exec(function (err, user) {
        if (err) { return done(err); }

        if (!user) {
            return done(null, false, { message: 'Incorrect username.' });
        }

        if ( user.authenticate( password ) ){
            return done(null, user);
        } else {
            return done(null, false, { message: 'Incorrect password.' });
        }
    });
  }
));

passport.serializeUser(function(user, done) {
  done(null, user.id);
});

passport.deserializeUser(function(id, done) {
  Users.findById(id).populate('roles').exec(function(err, user) {
      done(err, user);
  });
});