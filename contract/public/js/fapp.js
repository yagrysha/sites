var fu = {
    input: null,
    init: function (input) {
        this.input = input;
        input.change(function () {
            var len = this.files.length;

            if (this.files.length) {
                for (var i = 0; i < len; i++) {
                    fu.upload.call(fu, this.files[i]);
                }
                input.val('');
            }
        });
    },
    upload: function (file) {
        var li = $('<li>').html(file.name+' loading..').appendTo('ul');
        var formdata= new FormData();
            formdata.append("file", file);
        $.ajax({
            url: "/upload",
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (res) {
                if(res.status=='OK'){
                    fu.addFileLink(file, li, res.file.id)
                }else{
                    li.html(file.name+': '+res.error);
                }
            },
            error: function( jqXHR, textStatus, errorThrown){
                li.html(file.name+': '+textStatus+' '+ errorThrown);
            }
        });
    },
    addFileLink:function(file, li, id){
        li.html('<a href="/download/'+id+'">'+file.name+'</a> ');
        //todo кнопка удаления
        //$('<a>').addClass('btn btn-danger btn-delete').html('del')
    },
    delete:function(){
        var btn = $(this)
        $.post('/delete', {id:btn.data('id')}, function(res){
            if(res.status=='OK'){
                btn.parent().remove();
            }else{
                alert(res.error);
            }
        });
        return false;
    }
}
$(function () {
    fu.init($("input:file"));
    $('.btn-delete').click(fu.delete);
})

function p() {
    console.log(arguments);
}