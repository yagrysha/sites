var project={
    loadOperationsList: function(div, project){
        var mod = this;
        div.html('Список операций. Загрузка...');
        $.getJSON('/project/operations/'+project, function(data){
            if(data.error){
                alert(error.msg);
                return;
            }
            div.html('<h4>Текущие операции</h4>')
                .append(mod.renderOperations(data.items));
        })
    },
    renderOperations:function(ops){
        var html = '';
        ops.forEach(function(op){
            html += '' +
                '<p>' +
                    'Название:' + op.operation.name + '<br/>' +
                    'Описание:' + op.operation.description + '<br/>' +
                    'Проект:' + op.project.name + '<br/>' +
                    '<a href="/project/operation/'+op._id+'">Начать</a>' +
                '</p>' +
                '<hr class="divider">';
        })
        return html;
    },

    deleteProject: function(project_id){
        $.post('/project/delete',{project_id:project_id}, function(){
            //todo убрать из списка
        });
    },

    loadOperationLog: function(div, operation){
        var mod = this;
        div.html('Связанные операции. Загрузка...');

        $.ajax({
            type: 'GET',
            url: '/log/operation/'+operation,
            error: function(){
                div.html('Связанные операции. Произошла ошибка');
            },
            success: function(data){
                if (data.length){
                    div.html('<h4>Связанные операции</h4><hr/>')
                        .append(data);
                } else {
                    div.hide();
                }
            }
        });
    },

    loadOperationComment: function(div, operation){
        var mod = this;
        div.html('Комментарии к операции. Загрузка...');

        $.ajax({
            type: 'GET',
            url: '/project/operation/' + operation + '/comment',
            error: function(){
                div.html('Комментарии к операции. Произошла ошибка');
            },
            success: function(data){
                if (data.length){
                    div.html('<h4>Комментарии к операции</h4><hr/>')
                        .append(data);
                } else {
                    div.hide();
                }
            }
        });
    }
}