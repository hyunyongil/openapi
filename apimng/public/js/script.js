const url = 'https://'+window.location.hostname;

/*
layui.use('upload', function(){
    let upload = layui.upload;

    upload.render({
        elem: '.upload',
        url: url+'/admins/img_upload',
        before: function(obj){
            layer.load(1);
        },
        done: function(res, index, upload){
            layer.closeAll();
            if (res.code === 0) {
                layer.msg(res.msg, {time:2000});

                let key = $(this.item).attr('id');
                let old_img = $('input[name='+key+']').val();

                $('#'+key+'_img').attr('src', url+'/'+res.data.src);
                $('input[name='+key+']').val(res.data.src);

                if(old_img !== ''){
                    imgDelete(old_img);
                }
            }
        },
        error: function(index, upload){
            layer.closeAll();
        }
    });

    upload.render({
        elem: '.uploads',
        url: url+'/admins/img_upload',
        multiple: true,
        allDone: function(obj){
            layer.closeAll();
        },
        before: function(obj){
            layer.load(1);
        },
        done: function(res, index, upload){
            if (res.code === 0) {
                let html = '\
                            <li>\
                                <input type="hidden" class="'+$(this.item).attr('id')+'" value="'+res.data.src+'" />\
                                <img src="'+url+'/'+res.data.src+'" alt="" width="120" />\
                                <a href="javascript:void(0);" onclick="imgsDelete(this, \''+res.data.src+'\')">删除</a>\
                            </li>';
                $(this.item).prev('.img-list').append(html);
            }
        },
        error: function(index, upload){
            layer.closeAll();
        }
    });
});

function imgDelete(path){
    $.post(url+'/admins/img_delete', {'path':path}, function(data){});
}

function imgsDelete(obj, path){
    layer.confirm('确认要删除吗？',function(){
        $.post(url+'/admins/img_delete', {'path':path}, function(data){
            layer.closeAll();
            $(obj).parent('li').remove();
        });
    });
}
*/