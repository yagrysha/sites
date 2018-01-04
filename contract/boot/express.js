var express = require('express');
var path = require('path');
var passport = require('passport');
var config = require('config');

var MongoStore = require('connect-mongo')(express);
var mustAuthenticated = require('plugins/checkAuth');

module.exports = function (app) {
	// all environments
	app.set('port', process.env.PORT || config.port );
	app.set('views', path.join(__dirname + '/..', 'views'));
	app.set('view engine', 'jade');

	app.use(express.favicon());
	app.use(express.logger('dev'));
	app.use(express.json());
	//app.use(express.urlencoded());
	app.use(express.bodyParser());
	app.use(express.cookieParser());
	app.use(express.session({ 
		secret: "documents",
		store: new MongoStore({
	      url: config.db_url
	    }),
	    cookie: { maxAge: 60*60000 }
	}));
	app.use(express.methodOverride());
	app.use(passport.initialize());
	app.use(passport.session());

    app.use(express.static(path.join(__dirname + '/..', 'public')));

    app.use(mustAuthenticated);

	app.use(app.router);

	// development only
	if ('development' == app.get('env')) {
	  app.use(express.errorHandler());
	}
}