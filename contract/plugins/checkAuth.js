module.exports = function (req, res, next){
    if (req.isAuthenticated() || req.path==='/login'){
        next();
    } else {
        res.redirect('/login');
    }
};