//коды и текста напсшутся потом.
exports.Errors={
    ERROR: {
        code: 500,
        msg: 'Какаето ошибка. пока'
    },
    NOACCESS: {
        code: 100,
        msg: 'Нет прав на действие'
    },
    CNTCREATE: {
        code: 101,
        msg: 'Невозможно соаздать'
    },
    ROUTNOTFOUND: {
        code: 102,
        msg: 'маршрут не найден|не существует'
    },
    PROJECTNOTFOUND: {
        code: 103,
        msg: 'проект не найден|не существует'
    },
    PROJECTCLOSED:{
      code: 104,
        msg: 'проект недоступен'
    },
    OPOTHERUSER:{
        code:104,
        msg: 'операцию взял другойюзер'
    },
    OPCLOSE:{
        code:105,
        msg:'операция закрыта'
    },
    OPNOACCESS:{
        code:105,
        msg:'операция 1'
    },
    FIELDREQ:{
        code:105,
        msg:'Не заполнено обязательное поле'
    },
    FILENOACCESS:{
        code:105,
        msg:'Нет доступа к скачиваю файла'
    },

    // конструктор

    OPNOCHECK: 'Не выбрана операция',
    NOFIELDNAME: 'Не выбран идентификатор поля',
    FIELDDOUBLE: 'Внутри операции одинаковые идентификаторы',
    NOOPALIAS: 'Не выбран алиас операции',
    OPDOUBLE: 'Внутри роута одинаковые алиасы'
}
exports.getError = function(errorObj, err){
        var ret = {
            error: errorObj
        }
        if (err) {
            ret.err = err;
        }
        return ret
    }
