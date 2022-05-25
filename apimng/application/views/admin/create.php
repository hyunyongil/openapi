<!doctype html>
<html class="x-admin-sm" lang="ko">
<head>
    <?php echo $this->load->view('header'); ?>
</head>
<body>

<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form" id="data-form">
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>사용자아이디
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="username" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_pass" class="layui-form-label">
                    <span class="x-red">*</span>비밀번호
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="password" name="password" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="is_use" class="layui-form-label">
                    <span class="x-red">*</span>사용여부</label>
                <div class="layui-input-inline">
                    <select name="is_use">
                        <option value="1">사용함</option>
                        <option value="0">사용안함</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="privilege" class="layui-form-label">
                    <span class="x-red">*</span>권한설정</label>
                <div class="layui-input-inline">
                    <select name="privilege">
                        <option value="1">모든권한</option>
                        <option value="2">개발자</option>
                        <option value="3">게스트</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>프로젝트</label>
                <div class="layui-input-block">
                    <?php foreach($projects as $v): ?>
                    <input type="checkbox" name="project[<?php echo $v['id']; ?>]" value="<?php echo $v['id']; ?>" lay-skin="primary" title="<?php echo e($v['pj_name']); ?>" />
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                </label>
                <input type="button" value="추가하기" class="layui-btn" onclick="add();" />
            </div>
        </form>
    </div>
</div>

<script>
    layui.use(['form', 'layer']);

    let sending = 0;

    function add(){
        if (sending === 1) {
            return false;
        }
        sending = 1;

        let data = $('#data-form').serializeArray();

        $.post('<?php echo _url_('admin/create') ?>', {'data':data}, function(data){
            if (data.state === 0) {
                layer.msg(data.msg, {time:2000}, function(){
                    xadmin.close();
                    xadmin.father_reload();
                });
            } else {
                layer.msg(data.msg, {time:2000,anim:6});
                sending = 0;
            }
        });
    }
</script>

</body>
</html>
