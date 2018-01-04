//init def user#########################
var UserTypes = require('def/userType');

var defusers = [
    {
        username: "admin",
        password: "123",
        email: "admin@example.com",
        type: UserTypes.ADMIN,
        roles: []//добавим все роли для теста
    },
    {
        username: "jurist",
        password: "123",
        email: "jurist@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "financier",
        password: "123",
        email: "financier@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "curator",
        password: "123",
        email: "curator@example.com",
        type: UserTypes.MANAGER,
        // в админке выбрать роль куратор
        //roles: []
    },
    {
        username: "supervisor",
        password: "123",
        email: "supervisor@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "registrator",
        password: "123",
        email: "registrator@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "secretary",
        password: "123",
        email: "secretary@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "office",
        password: "123",
        email: "office@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "archive",
        password: "123",
        email: "archive@example.com",
        type: UserTypes.USER,
        // в админке выбрать роль
        //roles: []
    },
    {
        username: "client",
        password: "123",
        email: "client@example.com",
        type: UserTypes.CLIENT,
        // в админке выбрать роль клиент
        //roles: []
    }
];

module.exports = function(User, UserRole) {
    defusers.forEach(function (userdata) {
            User.findOne({username: userdata.username}, function (err, user) {
                if (err) throw err;

                if ( user ){
                    user.updateOne(userdata, function(err, cuser){
                        if (err) throw err;
                        if (cuser.isAdmin()) addAllRoles(cuser);
                    });
                } else {
                    User.create(userdata, function (err, cuser) {
                        if (err) throw err;
                        if (cuser.isAdmin()) addAllRoles(cuser);
                    });
                }
            });
        }
    );

    function addAllRoles(user) {
        UserRole.find({}, function (err, roles) {
            if (err)console.log('error find foles');
            else {
                roles.forEach(function (role) {
                    user.addRole(role);
                });
                user.save();
            }
        });
    }
};