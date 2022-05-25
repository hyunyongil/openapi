<!doctype html>
<html class="x-admin-sm" lang="ko">
<head>
    <?php echo $this->load->view('header'); ?>
</head>
<body>

<div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">Home</a>
            <a>
              <cite>Errors</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="새로고침">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('에러추가','<?php echo _url_('error/create_view'); ?>')"><i class="layui-icon"></i>추가하기</button>
                </div>
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="module_id" lay-filter="module_id">
                                <option value="0" <?php echo !array_val('module_id', $req) ? 'selected' : ''; ?>>=모듈선택=</option>
                                <?php foreach($modules as $v): ?>
                                <option value="<?php echo e($v['id']); ?>" <?php echo array_val('module_id', $req) == $v['id'] ? 'selected' : ''; ?>><?php echo e($v['mo_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="api_id">
                                <option value="-1">=api선택=</option>
                                <option value="0">Common</option>
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="search">
                                <i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th width="80">Operation</th>
                            <th>api명</th>
                            <th>State</th>
                            <th>Code</th>
                            <th>Message</th>
                            <th>Description</th>
                            <th>순서배열</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($datas['data'] as $v): ?>
                        <tr>
                            <td class="td-manage">
                                <a title="수정하기"  onclick="xadmin.open('수정하기','<?php echo _url_('error/edit_view?id='.$v['id']); ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="삭제" onclick="destroy(this,'<?php echo $v['id']; ?>')" href="javascript:void(0);">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                            </td>
                            <td><?php echo e($v['api_name']); ?></td>
                            <td><?php echo e($v['state']); ?></td>
                            <td><?php echo e($v['code']); ?></td>
                            <td><?php echo e($v['message']); ?></td>
                            <td><?php echo e($v['description']); ?></td>
                            <td><?php echo e($v['sort']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <?php echo $datas['paging']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['form', 'layer'], function(){
        form = layui.form;

        form.on('select(module_id)', function(data){
            let module_id = parseInt(data.value);

            get_module_api(module_id);
        });
    });

    $(function(){
        var module_id = parseInt($('select[name=module_id]').val());
        get_module_api(module_id);
    });

    function get_module_api(module_id){
        if (module_id === 0) return false;

        $.get('<?php echo _url_('api/get_module_api') ?>?module_id='+module_id, function(data){
            var api_id = parseInt('<?php echo array_val('api_id', $req); ?>');
            var commonSel = api_id === 0 ? 'selected' : '';

            var html = '<option value="-1">=api선택=</option><option value="0" '+commonSel+'>Common</option>';

            $(data.api_list).each(function(k, v){
                var sel = api_id === parseInt(v.id) ? 'selected' : '';
                html += '<option value="'+v.id+'" '+sel+'>'+v.api_name+'</option>';
            });

            $('select[name=api_id]').html(html);
            form.render('select');
        });
    }

    function destroy(obj,id){
        layer.confirm('삭제하시겠습니까?',function(){
            $.post('<?php echo _url_('error/del'); ?>', {'id': id}, function(data){
                if (data.state === 0) {
                    layer.msg(data.msg, {time:2000}, function(){
                        window.location.reload();
                    });
                } else {
                    layer.msg(data.msg,{time:2000,anim:6});
                }
            });
        });
    }
</script>

</body>
</html>
