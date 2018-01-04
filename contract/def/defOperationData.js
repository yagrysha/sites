// заглушка для связанных операций
// для подключения надо на странице проекта нажать кнопку Заглушка

module.exports = [
    {
        "startTime" : "2014-03-24T11:15:56.005Z",
        "status" : 2,
        "createdAt" : "2014-03-24T11:15:43.617Z",
        "operation" : {
            "alias" : "promise1",
            "name" : "Подтверждение операции бухгалтером.",
            "description" : "Подтверждение операции бухгалтером.",
            fields:[
                {
                    name:'comment',
                    label:'Комментарий',
                    description:'Напишите комментарий',
                    type:'text',
                    required: false,
                    settings:{
                        "default": 1
                    }
                }
            ],
            "controls" : [
                {
                    "name" : "success",
                    "title" : "OK",
                    "description" : "Операция успешно выполнена.",
                    "nextOp" : [
                        1
                    ]
                },
                {
                    "name" : "error",
                    "title" : "Отмена",
                    "description" : "Операция не выполнена. Невозможно выпонить опеацию. статус Error.",
                    "nextOp" : [
                        0
                    ]
                }
            ],
            "access" : [
                "curator"
            ],
            "related" : []
        },
        "data" : [
            {
                "name" : "комментарий",
                "value" : "подтверждено"
            }
        ]
    },
    {
        "startTime" : "2014-03-24T11:15:56.005Z",
        "status" : 3,
        "createdAt" : "2014-03-24T11:15:43.617Z",
        "operation" : {
            "alias" : "promise2",
            "name" : "Подтверждение операции юристом.",
            "description" : "Подтверждение операции юристом.",
            fields:[
                {
                    name:'comment',
                    label:'Комментарий',
                    description:'Напишите комментарий',
                    type:'text',
                    required: false,
                    settings:{
                        default: 1,
                    },
                }
            ],
            "controls" : [
                {
                    "name" : "success",
                    "title" : "OK",
                    "description" : "Операция успешно выполнена.",
                    "nextOp" : [
                        1
                    ]
                },
                {
                    "name" : "error",
                    "title" : "Отмена",
                    "description" : "Операция не выполнена. Невозможно выпонить опеацию. статус Error.",
                    "nextOp" : [
                        0
                    ]
                }
            ],
            "access" : [
                "curator"
            ],
            "related" : []
        },
        "data" : [
            {
                "name" : "комментарий",
                "value" : "неверно заполнены данные"
            }
        ]
    },
    {
        "startTime" : "2014-03-24T11:15:56.005Z",
        "status" : 1,
        "createdAt" : "2014-03-24T11:15:43.617Z",
        "operation" : {
            "alias" : "promise",
            "name" : "Подтверждение операции менеджером.",
            "description" : "Подтверждение операции менеджером.",
            fields:[
                {
                    name:'comment',
                    label:'Комментарий',
                    description:'Напишите комментарий',
                    type:'text',
                    required: false,
                    settings:{
                        default: 1
                    },
                }
            ],
            "controls" : [
                {
                    "name" : "success",
                    "title" : "OK",
                    "description" : "Операция успешно выполнена.",
                    "nextOp" : [
                        1
                    ]
                },
                {
                    "name" : "error",
                    "title" : "Отмена",
                    "description" : "Операция не выполнена. Невозможно выпонить опеацию. статус Error.",
                    "nextOp" : [
                        0
                    ]
                }
            ],
            "access" : [
                "curator"
            ],
            "related" : ["promise1", "promise2"]
        },
        "data" : [

        ]
    }
]