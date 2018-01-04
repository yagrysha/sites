var UserTypes = {
    NOBODY: 0,
    CLIENT: 1,
    USER: 2,
    MANAGER: 3,
    ADMIN: 4,

    description: [
        'Никто',
        'Клиент',
        'Служащий',
        'Менеджер',
        'Администратор'
    ],

    redirect: [
        'home', // хз какой тут адрес,
        'client',
        'user',
        'manager',
        'admin'
    ],

    asArray: function () {
        var ret = [];
        for(p in this){
            if(typeof this[p]=="number"){
                ret.push(this[p])
            }
        }
        return ret;
    },
    isAdmin: function (user){
        return (user.type==UserTypes.ADMIN);
    }
    ,
    isManager: function (user){
        return (user.type==UserTypes.MANAGER);
    }
};
module.exports = UserTypes;