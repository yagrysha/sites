
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
    module: 'admin',

    indexAction: function () {
        actions.res.render('admin/index');
    },

    accessAction: function () {
        if (UserType.isAdmin(this.user)){
            return this.next();
        } else {
            this.res.json( error( ProjectErrors.NOACCESS ) );
        }
    }
};

function error(errorObj, err) {
    actions.res.status(500);
    var ret = {
        error: errorObj
    }
    if (err) {
        ret.err = err;
    }
    return ret
}

var ProjectErrors = {
    NOACCESS: {
        code: 100,
        msg: 'Нет прав на действие'
    }
}
