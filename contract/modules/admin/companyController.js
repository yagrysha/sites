var async = require('async');

var Company = require('models/company');

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
     * список компаний
     * @returns request
     */
    listAction: function() {
        Company.find( {}, function (err, companies) {
            if (err) return actions.next(err);

            actions.res.render('admin/company', {
                companies: companies
            });
        });
    },

    /**
     * создание компании
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
        actions.res.render('admin/company_new_form');
    },

    postCreate:function(){
        var obj = this.req.body;

        var company = new Company({
            name: obj.name
        });

        company.save( function( err, company ){
            if(err) return actions.next(err);

            actions.res.redirect('/admin/company');
        });
    },

    /**
     * редактирование компании
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
        Company.findById(id, function (err, company_data) {
            if(err) return actions.next(err);

            actions.res.render('admin/company_edit_form', {
                company: company_data
            });
        });
    },

    postEdit:function( id ){
        var obj = this.req.body;

        Company.findById( id, function (err, company) {
            if (err) return actions.next(err);

            company.name = obj.name;

            company.save( function( err, company ){
                if (err) return actions.next(err);

                actions.res.redirect('/admin/company/edit/' + company._id);
            });
        });
    },

    /**
     * удаление компании.
     * @returns response
     */
    deleteAction: function () {
        var id = this.req.params.id;

        if (id) {
            Company.findByIdAndRemove( id, function (err, user) {
                if(err) return actions.next(err);

                actions.res.redirect('/admin/company');
            });
        }
    }
};