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
                <label for="pj_name" class="layui-form-label">
                    <span class="x-red">*</span>프로젝트명
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="pj_name" name="pj_name" value="<?php echo e($field['pj_name']); ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="host" class="layui-form-label">
                    Host
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="host" name="host" value="<?php echo e($field['host']); ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="description" class="layui-form-label">
                    상세설명
                </label>
                <div class="layui-input-block">
                    <textarea id="description" name="description" class="layui-textarea"><?php echo e($field['description']); ?></textarea>
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

        $.post('<?php echo _url_('project/edit') ?>', {'id': id, 'data':data}, function(data){
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
