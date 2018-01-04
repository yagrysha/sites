var aRoles = [
    {
        alias: 'user',
        name: 'Пользователь'
    },
    {
        alias: 'curator',
        name: 'Куратор'
    },
    {
        alias: 'supervisor',
        name: 'Руководитель'
    },
    {
        alias: 'jurist',
        name: 'Юрист'
    },
    {
        alias: 'financier',
        name: 'Бухгалтер'
    },
    {
        alias: 'registrator',
        name: 'Оформитель'
    },
    {
        alias: 'secretary',
        name: 'Секретарь'
    },
    {
        alias: 'office',
        name: 'Канцелярия'
    },
    {
        alias: 'courier',
        name: 'Курьер'
    },
    {
        alias: 'archive',
        name: 'Архиватор'
    }
];

/**
 * Добавление ролей в базу, если база пуста
 *
 * */
module.exports = function(UserRole) {
    UserRole.find({}, function(err, roles){
        if (err) return console.log(err);
        if (roles.length){
            //console.log('roles in base');
        } else {
            console.log('add default roles');
            aRoles.forEach( saveRole );
        }
    })

    var saveRole = function( obj ){
        UserRole.create({
            'alias': obj.alias,
            'name': obj.name
        }, function(err, role ){
            if (err) return;
        });
    }
};