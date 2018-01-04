var async = require('async');

var User = require('models/user');
var UserRole = require('models/userRole');

var UserType = require('def/userType');

/**
 * если идея с использованием этой функции бцдет удачной то вынести кудато , чтобы использовать в других модулях
 * @param action - название экшена
 * @param options - что-то что передастся экшену
 * @returns {Function}
 * //todo что то тут перемудрил..
 */
var controller = {
    run: function (action, options) {

        var actionName = action + 'Action';
        if ('function' != typeof actions[actionName]) {
            throw Error('Wrong action name ' + actionName);
        }
        return function (req, res, next) {
            actions.req = req;
            actions.res = res;
            actions.next = next;
            actions.params = req.params;
            actions.body = req.body;
            actions.user = req.user;
            actions.action = action;

            actions[actionName](options);
            /*var viewData = actions[actionName](options);
             if (viewData) {
             controller.renderView(viewData, res);
             }*/
        }
    },
    renderView: function (view, res, json) {
        view._user = actions.req.user;
        if (!view._template) {
            view._template = actions.action;
        }
        view._debug = require('util').inspect(view);
        //потом переключить вывод если нужно всё выдавать в json
        if (json)
            res.json(view);
        else
            res.render(actions.module + '/' + view._template, view);
    }
}

module.exports = controller;

var actions = {

    /**
     * список юзеров
     * @returns request
     */
    listAction: function() {
        User.find({}).populate('roles').exec( function (err, users) {
            if (err) return actions.next(err);

            //console.log(UserRole.description);

            actions.res.render('admin/users', {
                users: users,
                types: UserType.description
            });
        });
    },

    /**
     * создание юзера
     * @param doСreate
     * @returns response
     */
    createAction: function (doСreate) {
        if (doСreate) {
            return this.postCreate();
        } else {
            return this.getCreate();
        }
    },

    getCreate:function(){
        var result = {};

        async.parallel([
            function(callback){
                UserRole.find( {}, function(err, roles){
                    result.roles = roles;
                    callback();
                });
            },
            function(callback){
                result.types = UserType.description;
                callback();
            }
        ],function(err, results) {
            if (err) return actions.next(err);

            actions.res.render('admin/user_new_form', result);
        });
    },

    postCreate:function(){
        var obj = this.req.body;

        var user = new User({
            username: obj.username,
            password: obj.password,
            email: obj.email,
            type: obj.type,
            roles: obj.roles
        });

        user.save( function( err, user ){
            if(err) return actions.next(err);

            actions.res.redirect('/admin/users');
        });
    },

    /**
     * редактирование юзера
     * @param doEdit
     * @returns response
     */
    editAction: function (doEdit) {
        var id = this.req.params.id;

        if (doEdit) {
            return this.postEdit( id );
        } else {
            return this.getEdit( id );
        }
    },

    getEdit:function( id ){
        var result = {};

        async.parallel([
            function(callback){
                UserRole.find( {}, function(err, roles){
                    result.roles = roles;
                    callback();
                });
            },
            function(callback){
                result.types = UserType.description;
                callback();
            },
            function(callback){
                User.findById( id, function (err, user) {
                    result.user = user;
                    callback();
                });
            }
        ],function(err, results) {
            if (err) return actions.next(err);

            actions.res.render('admin/user_edit_form', result);
        });
    },

    postEdit:function( id ){
        var obj = this.req.body;

        User.findById( id, function (err, user) {
            if (err) return actions.next(err);

            user.username = obj.username;
            user.email = obj.email;
            user.type = obj.type;
            user.roles = obj.roles;

            user.save( function( err, user ){
                if (err) return actions.next(err);

                actions.res.redirect('/admin/users/edit/' + user._id);
            });
        });
    },

    /**
     * удаление юзера.
     * @returns response
     */
    deleteAction: function () {
        var id = this.req.params.id;

        if (id) {
            User.findByIdAndRemove( id, function (err, user) {
                if(err) return actions.next(err);

                actions.res.redirect('/admin/users');
            });
        }
    }
};