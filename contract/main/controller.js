var UserTypes = require('def/userType');

module.exports = function(actions){
    var controller = {
        run: function (action, options) {
            var actionName = action + 'Action';
            if ('function' != typeof actions[actionName]) {
                throw Error('Wrong action name ' + actionName);
            }
            return function (req, res) {
                actions.req = req;
                actions.res = res;
                actions.params = req.params;
                actions.body = req.body;
                actions.files = req.files;
                actions.user = req.user;
                actions.action = action;
                actions.render = function(err, view){
                    if(err){
                        controller.renderView({error:err}, res);
                    }else{
                        controller.renderView(view, res);
                    }
                }
                var viewData = actions[actionName](options);
                if (viewData) {
                    controller.renderView(viewData, res);
                }
            }
        },
        renderView: function (view, res, json) {
            var role_template = UserTypes.redirect[ actions.user.type ];
            if (!view._template) {
                view._template = actions.action;
            }
            //потом переключить вывод если нужно всё выдавать в json
            if (json || view._template=='json'){
                delete view._template
                res.json(view);
            }else {
                view._user = actions.user;
                view._debug = require('util').inspect(view);
                if (view.error) {
                    res.status(500);
                    res.render('home/error', view);
                } else{
                    res.render(role_template + '/' +actions.module + '/' + view._template, view);
                }
            }
        }
    }
    return controller;
}