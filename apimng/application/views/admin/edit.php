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
                    <input type="text" id="username" name="username" value="<?php echo e($field['username']); ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="password" class="layui-form-label">
                    <span class="x-red">*</span>비밀번호
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="password" name="password" autocomplete="off" class="layui-input" placeholder="입력하지 않으면 수정않함">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="is_use" class="layui-form-label">
                    <span class="x-red">*</span>사용여부</label>
                <div class="layui-input-inline">
                    <select name="is_use">
                        <option value="1" <?php echo $field['is_use'] == '1' ? 'selected' : '' ?>>사용함</option>
                        <option value="0" <?php echo !$field['is_use'] ? 'selected' : '' ?>>사용안함</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="privilege" class="layui-form-label">
                    <span class="x-red">*</span>권한설정</label>
                <div class="layui-input-inline">
                    <select name="privilege">
                        <option value="1" <?php echo $field['privilege'] == '1' ? 'selected' : '' ?>>모든권한</option>
                        <option value="2" <?php echo $field['privilege'] == '2' ? 'selected' : '' ?>>개발자</option>
                        <option value="3" <?php echo $field['privilege'] == '3' ? 'selected' : '' ?>>게스트</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>프로젝트</label>
                <div class="layui-input-block">
                    <?php foreach($projects as $v): ?>
                    <input type="checkbox" name="project[<?php echo $v['id']; ?>]" value="<?php echo $v['id']; ?>" lay-skin="primary" title="<?php echo e($v['pj_name']); ?>" <?php echo in_array($v['id'], $adminProjects) ? 'checked' : ''; ?> />
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                </label>
                <input type="button" value="업데이트" class="layui-btn" onclick="edit(this, '<?php echo $field['id']; ?>');" />
            </div>
        </form>
    </div>
</div>

<script>
    layui.use(['form', 'layer']);

    let sending = 0;

    function edit(obj, id){
        if (sending === 1) {
            return false;
        }
        sending = 1;

        let data = $('#data-form').serializeArray();

        $.post('<?php echo _url_('admin/edit') ?>', {'id': id, 'data':data}, function(data){
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
