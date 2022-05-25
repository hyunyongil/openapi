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
                <label for="copydata" class="layui-form-label">
                    api</label>
                <div class="layui-input-inline">
                    <select lay-filter="module_id">
                        <option value="0">=모듈선택=</option>
                        <?php foreach($modules as $v): ?>
                        <option value="<?php echo e($v['id']); ?>" <?php echo array_val('module_id', $cur_module) == $v['id'] ? 'selected' : ''; ?>><?php echo e($v['mo_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <select lay-filter="api_id" name="api_id">
                        <option value="0">=api선택=</option>
                        <?php foreach($module_api as $v): ?>
                        <option value="<?php echo e($v['id']); ?>" <?php echo $field['api_id'] == $v['id'] ? 'selected' : ''; ?>><?php echo e($v['api_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="state" class="layui-form-label">
                    <span class="x-red">*</span>State
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="state" name="state" value="<?php echo e($field['state']); ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="code" class="layui-form-label">
                    <span class="x-red">*</span>Code
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="code" name="code" value="<?php echo e($field['code']); ?>" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="message" class="layui-form-label">
                    Message
                </label>
                <div class="layui-input-block">
                    <textarea id="message" name="message" class="layui-textarea"><?php echo e($field['message']); ?></textarea>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="description" class="layui-form-label">
                    Description
                </label>
                <div class="layui-input-block">
                    <textarea id="description" name="description" class="layui-textarea"><?php echo e($field['description']); ?></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="sort" class="layui-form-label">
                    순서배열
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="sort" name="sort" value="<?php echo e($field['sort']); ?>" autocomplete="off" class="layui-input">
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
    layui.use(['form', 'layer'], function(){
        form = layui.form;

        form.on('select(module_id)', function(data){
            let module_id = parseInt(data.value);

            if (module_id === 0) return false;

            $.get('<?php echo _url_('api/get_module_api') ?>?module_id='+module_id, function(data){
                var html = '<option value="0">=api선택=</option>';

                $(data.api_list).each(function(k, v){
                    html += '<option value="'+v.id+'">'+v.api_name+'</option>';
                });

                $('select[name=api_id]').html(html);
                form.render('select');
            });
        });
    });

    let sending = 0;

    function edit(obj, id){
        if (sending === 1) {
            return false;
        }
        sending = 1;

        let data = $('#data-form').serializeArray();

        $.post('<?php echo _url_('error/edit') ?>', {'id': id, 'data':data}, function(data){
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
