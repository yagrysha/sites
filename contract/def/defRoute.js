//всесто этого потом управление через админку
var defRoute = {
    alias:'document',
    name:'Документ. заключение договора',
    description: 'пока есдинстенный маршрут проекта(документа,)',
    operations:[
        {//2
            alias:'start',
            name:'Выбор компании и назначение куратора',
            description:'Операция лоступна кураторам. Дале они крипязываются к этому проекту.',
            access:['curator'],
            fields:[
                {
                    title: "Номер", // это поле будет отображаться в логах
                    name:'text',
                    label:'Номер',
                    description:'введите какойто номер',
                    type:'text',
                    required: false,
                    settings:{
                        default:"1"
                    },
                    attr:{
                        maxlength:100
                    }
                },
                {
                    title: "Компания",
                    name:'company',
                    label:'Выберите компанию',
                    description:'введите какойто номер',
                    type:'select',
                    required: true,
                    settings:{
                        datasource: 'getcompanies',
                        setdata: 'setcompanies'
                    }
                }
            ],
            //попробовать
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["uploadfile"],
                    doAction: ["setCurator","activateProject"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["start"]
                },
                reset: {
                    title:'Сброс',
                    description:'Отмена операции. Статус меняется на NEW'
                },
                save: {
                    title:'Сохранить',
                    description:'Сохранитью просто сохраянются данные, статус не меняется'
                }
            }
        },
        {//3
            alias:'uploadfile',
            name:'Загрузка документа',
            description:'Требуется загрузить документ для проверки нашими сотрудниками',
            access:['user'],
            fields:[
                {
                    title: "Файл",
                    name:'file',
                    label:'Файл',
                    description:'Выберите файл',
                    type:'file',
                    required: false,
                    settings:{
                        setdata: 'uploadfile'
                    }
                }
            ],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["check_supervisor", "check_jurist", "check_financier"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["uploadfile"]
                }
            }
        },
        {//4.1
            alias:'check_supervisor',
            name:'Проверка документа руководителем',
            description:'Проверка документа на соответствие возможности выполнения',
            related: ["uploadfile"],
            access:['supervisor'],
            fields:[
                {
                    title: "Комментарий",
                    name:'comment',
                    label:'Комментарий',
                    description:'Результат проверки документа',
                    type:'text',
                    required:'true',
                    settings:{
                        default:"1"
                    },
                    attr:{
                        maxlength:100
                    }
                }
            ],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["check_curator"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["check_supervisor"]
                }
            }
        },
        {//4.2
            alias:'check_jurist',
            name:'Проверка документа юристом',
            description:'Проверка документа на соответствие законодательству',
            related: ["uploadfile"],
            access:['jurist'],
            fields:[
                {
                    title: "Комментарий",
                    name:'comment',
                    label:'Комментарий',
                    description:'Результат проверки документа',
                    type:'text',
                    required:'true',
                    settings:{
                        default:"1"
                    },
                    attr:{
                        maxlength:100
                    }
                }
            ],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["check_curator"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["check_jurist"]
                }
            }
        },
        {//4.3
            alias:'check_financier',
            name:'Проверка документа юристом',
            description:'Проверка документа на соответствие бухгалтерских аспектов',
            related: ["uploadfile"],
            access:['financier'],
            fields:[
                {
                    title: "Комментарий",
                    name:'comment',
                    label:'Комментарий',
                    description:'Результат проверки документа',
                    type:'text',
                    required:'true',
                    settings:{
                        default:"1"
                    },
                    attr:{
                        maxlength:100
                    }
                }
            ],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["check_curator"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["check_financier"]
                }
            }
        },
        {//5
            alias:'check_curator',
            name:'Анализ результатов проверки куратором',
            description:'Если результаты положительные, то отправка оформителю, если нет - то возврат клиенту',
            related: ["check_supervisor", "check_jurist", "check_financier"],
            access:['curator'],

            controls:{
                success: {
                    title:'Направить оформителю',
                    description:'Операция успешно выполнена.',
                    nextOp:["pdf"]
                },
                error: {
                    title:'Возврат клиенту',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["uploadfile"]
                }
            }
        },
        {//5.1
            alias:'pdf',
            name:'Оформление договора',
            description:'Перевод документа в PDF формат для печати.',
            related: ["uploadfile"],
            access:['registrator'],
            fields:[
                {
                    title: "PDF",
                    name:'file',
                    label:'PDF',
                    description:'Выберите файл',
                    type:'file',
                    required: false,
                    settings:{
                        setdata: 'uploadfile'
                    }
                }
            ],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["details_financier"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["pdf"]
                }
            }
        },
        {//5.2
            alias:'details_financier',
            name:'Внесение реквизитов договора в 1С',
            description:'Скачайте PDF. Внесение реквизитов договора в 1С',
            related: ["pdf"],
            access:['financier'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["details_curator"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["details_financier"]
                }
            }
        },
        {//5.3
            alias:'details_curator',
            name:'Внесение реквизитов договора в карточку реестра (номер, даты, направление, пр.)',
            description:'Скачайте PDF. Внесите реквизиты',
            related: ["pdf"],
            access:['curator'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["print"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["details_curator"]
                }
            }
        },
        {//6
            alias:'print',
            name:'Печать договора (в 2-х экземплярах)',
            description:'Передача договоров на подпись директором и печать',
            related: ["pdf"],
            access:['secretary'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["get_agreement"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["print"]
                }
            }
        },
        {//7
            alias:'get_agreement',
            name:'Получение оформленного договора (со стороны исполнителя)',
            description:'Получение оформленного договора (со стороны исполнителя)',
            related: [],
            access:['secretary'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["send_agreement"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["get_agreement"]
                }
            }
        },
        {//8
            alias:'send_agreement',
            name:'Отправка оформленного договора (со стороны исполнителя) в канцелярию',
            description:'Отправка оформленного договора (со стороны исполнителя) в канцелярию',
            related: [],
            access:['secretary'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["confirm"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["send_agreement"]
                }
            }
        },
        {//9
            alias:'confirm',
            name:'Подтверждение получения оформленного договора канцелярией',
            description:'Подтверждение получения оформленного договора (со стороны исполнителя) канцелярией',
            related: [],
            access:['office'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["courier"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["confirm"]
                }
            }
        },
        {//10
            alias:'courier',
            name:'Передача оформленного договора курьеру Клиента',
            description:'Передача оформленного договора (со стороны исполнителя) курьеру Клиента',
            related: [],
            access:['office'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["approve_client"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["courier"]
                }
            }
        },
        {//11
            alias:'approve_client',
            name:'Подтверждение получения оформленного договора',
            description:'Подтверждение получения оформленного договора (со стороны исполнителя) Клиентом',
            related: [],
            access:['user'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["2side_agreement"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["approve_client"]
                }
            }
        },
        {//12
            alias:'2side_agreement',
            name:'Получение оформленного договора (с 2-х сторон) канцелярией',
            description:'Получение оформленного договора (с 2-х сторон) канцелярией',
            related: [],
            access:['office'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["2side_secretary"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["2side_agreement"]
                }
            }
        },
        {//13
            alias:'2side_secretary',
            name:'Передача договора секретарю',
            description:'Передача оформленного договора (с 2-х сторон) из канцелярии секретарю',
            related: [],
            access:['office'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["confirm_2side_secretary"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["2side_secretary"]
                }
            }
        },
        {//14

            alias:'confirm_2side_secretary',
            name:'Подтверждение получения договора (с 2-х сторон)',
            description:'Подтверждение получения оформленного договора (с 2-х сторон) Секретарем',
            related: [],
            access:['secretary'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["scan"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["confirm_2side_secretary"]
                }
            }
        },
        {//15
            alias:'scan',
            name:'Сканирование договора',
            description:'Сканирование договора, загрузка jpg документа в систему',
            related: [],
            access:['secretary'],
            fields:[
                {
                    title: "Скан договора",
                    name:'file',
                    label:'Скан договора',
                    description:'Выберите файл',
                    type:'file',
                    required: false,
                    settings:{
                        setdata: 'uploadfile'
                    }
                }
            ],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["send_archive"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["scan"]
                }
            }
        },
        {//16
            alias:'send_archive',
            name:'Отправка оригинала в архив',
            description:'Отправка оригинала оформленного договора (с 2-х сторон) в архив',
            related: [],
            access:['secretary'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:["archive"]
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["end"]
                }
            }
        },

        {//17
            alias:'end',
            name:'Архивирование документа в папку',
            description:'Подтверждение получения оригинала оформленного договора (с 2-х сторон). Архивирование документа в папку',
            related: [],
            access:['archive'],
            controls:{
                success: {
                    title:'OK',
                    description:'Операция успешно выполнена.',
                    nextOp:[],
                    doAction:"closeProject"
                },
                error: {
                    title:'Отмена',
                    description:'Операция не выполнена. Невозможно выпонить опеацию. статус Error.',
                    nextOp: ["end"]
                }
            }
        }
    ]
}

module.exports = function(Route) {
    Route.findOneAndUpdate({alias: "document"}, defRoute, function(err, route){
        if(err){
            console.log(err);
        }else{
            if(!route){
                Route.create(defRoute, function(err, route){
                    if(err){
                        console.log('error creating rout'+err);
                    }else{
                        console.log('rout created'+route);
                    }
                });
            }

            /*  console.log(route);
             console.log('###');
             console.log(route.operations[0].fields);
             console.log('###');
             console.log(route.operations[0].controls);*/
        }
    });
};

