var mongoose = require('mongoose');
var passport = require('passport');

var User = require('models/user');

var UserTypes = require('def/userType');

module.exports.controller = function(app) {
	app.get('/', function(req, res) {
        var redirect = UserTypes.redirect[ req.user.type ];
		res.redirect( redirect );
 	});

	app.get('/login', function(req, res) {
	  	res.render('index/login');
 	});

 	app.post('/login', passport.authenticate('local', { 
			successRedirect: '/',
            failureRedirect: '/login'
        })
 	);

 	app.get('/logout', function(req, res) {
  		req.logout();
  		res.redirect('/login');
	});
}