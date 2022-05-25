<!doctype html>
<html class="x-admin-sm" lang="ko">
<head>
    <?php echo $this->load->view('header'); ?>
</head>
<body>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th width="80">Operation</th>
                            <th>모듈명</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($datas as $v): ?>
                        <tr>
                            <td class="td-manage">
                                <a title="삭제" onclick="destroy(this,'<?php echo $v['id']; ?>')" href="javascript:void(0);">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                            </td>
                            <td><?php echo e($v['mo_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function destroy(obj,id){
        layer.confirm('삭제하시겠습니까?',function(){
            $.post('<?php echo _url_('project/module_del'); ?>', {'id': id}, function(data){
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
