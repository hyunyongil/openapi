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
                <div class="layui-fluid">
                    <div class="layui-row">
                        <form class="layui-form" id="data-form">
                            <div class="layui-form-item">
                                <label for="api_name" class="layui-form-label">
                                    <span class="x-red">*</span>API명</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="api_name" name="api_name" value="<?php echo e($field['api_name']); ?>" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label for="api_path" class="layui-form-label">
                                    <span class="x-red">*</span>PATH</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="api_path" name="api_path" value="<?php echo e($field['api_path']); ?>" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label for="api_method" class="layui-form-label">
                                    <span class="x-red">*</span>METHOD</label>
                                <div class="layui-input-inline">
                                    <select name="api_method">
                                        <option value="GET" <?php echo $field['api_method'] == 'GET' ? 'selected' : ''; ?>>GET</option>
                                        <option value="POST" <?php echo $field['api_method'] == 'POST' ? 'selected' : ''; ?>>POST</option>
                                        <option value="PUT" <?php echo $field['api_method'] == 'PUT' ? 'selected' : ''; ?>>PUT</option>
                                        <option value="DELETE" <?php echo $field['api_method'] == 'DELETE' ? 'selected' : ''; ?>>DELETE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="with_token" class="layui-form-label">
                                    <span class="x-red">*</span>TOKEN</label>
                                <div class="layui-input-inline">
                                    <select name="with_token">
                                        <option value="1" <?php echo $field['with_token'] ? 'selected' : ''; ?>>필수</option>
                                        <option value="0" <?php echo !$field['with_token'] ? 'selected' : ''; ?>>비필수</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="version" class="layui-form-label">
                                    API버전</label>
                                <div class="layui-input-inline">
                                    <input type="number" id="version" name="version" value="<?php echo e($field['version']); ?>" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label for="module_name" class="layui-form-label">
                                    <span class="x-red">*</span>모듈</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="module_name" list="modules" name="module_name" value="<?php echo e($field['mo_name']); ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                                    <datalist id="modules">
                                        <?php foreach($modules as $v): ?>
                                        <option value="<?php echo e($v['mo_name']); ?>"><?php echo e($v['mo_name']); ?></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="status" class="layui-form-label">
                                    API상태</label>
                                <div class="layui-input-inline">
                                    <select name="status">
                                        <option value="0" <?php echo !$field['status'] ? 'selected' : ''; ?>>테스트</option>
                                        <option value="1" <?php echo $field['status'] ? 'selected' : ''; ?>>사용중</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="desc" class="layui-form-label">REQUEST PARAMETERS</label>
                                <div class="layui-input-block layui-card-body layui-table-main">
                                    <table class="layui-table req-table">
                                        <thead id="req-param-list">
                                        <tr>
                                            <th><input type="button" value="추가" class="layui-btn" onclick="add_req_row();" /></th>
                                            <th>요청KEY</th>
                                            <th>데이터 타입</th>
                                            <th>필수여부</th>
                                            <th>상세설명</th>
                                            <th>순서배열</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($req_param as $v): ?>
                                        <tr class="index-<?php echo e($v['level']+1); ?>">
                                            <input type="hidden" name="req_parent@<?php echo e($v['id']); ?>" value="<?php echo e($v['parent_id']); ?>" />
                                            <td style="font-size:14px;"><?php echo $v['level'] ? '╚'.str_repeat('════', $v['level']) : '╠'; ?></td>
                                            <td><input type="text" name="req_key@<?php echo e($v['id']); ?>" value="<?php echo e($v['req_key']); ?>" autocomplete="off" class="layui-input" /></td>
                                            <td>
                                                <select name="req_type@<?php echo e($v['id']); ?>">
                                                    <option value="string" <?php echo $v['req_type'] == 'string' ? 'selected' : ''; ?>>String</option>
                                                    <option value="int" <?php echo $v['req_type'] == 'int' ? 'selected' : ''; ?>>int</option>
                                                    <option value="float" <?php echo $v['req_type'] == 'float' ? 'selected' : ''; ?>>float</option>
                                                    <option value="boolean" <?php echo $v['req_type'] == 'boolean' ? 'selected' : ''; ?>>boolean</option>
                                                    <option value="array" <?php echo $v['req_type'] == 'array' ? 'selected' : ''; ?>>array</option>
                                                    <option value="object" <?php echo $v['req_type'] == 'object' ? 'selected' : ''; ?>>object</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="req_mode@<?php echo e($v['id']); ?>">
                                                    <option value="1" <?php echo $v['req_mode'] ? 'selected' : ''; ?>>필수</option>
                                                    <option value="0" <?php echo !$v['req_mode'] ? 'selected' : ''; ?>>비필수</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="req_description@<?php echo e($v['id']); ?>" value="<?php echo e($v['req_description']); ?>" autocomplete="off" class="layui-input" /></td>
                                            <td><input type="number" name="req_sort@<?php echo e($v['id']); ?>" value="<?php echo $v['req_sort']; ?>" autocomplete="off" class="layui-input" /></td>
                                            <td>
                                                <input type="button" value="추가" class="layui-btn" onclick="add_req_row(this, <?php echo $v['id']; ?>, <?php echo $v['level']; ?>)" />
                                                <input type="button" value="삭제" class="layui-btn layui-btn-danger" onclick="remove_req_row(this);" />
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="desc" class="layui-form-label">RESPONSE PARAMETERS</label>
                                <div class="layui-input-block layui-card-body layui-table-main">
                                    <table class="layui-table res-table">
                                        <thead id="res-param-list">
                                        <tr>
                                            <th><input type="button" value="추가" class="layui-btn" onclick="add_res_row();" /></th>
                                            <th>리턴KEY</th>
                                            <th>데이터 타입</th>
                                            <th>상세설명</th>
                                            <th>순서배열</th>
                                            <th>Operation</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($res_param as $v): ?>
                                        <tr class="index-'+curLevel+'">
                                            <input type="hidden" name="res_parent@<?php echo e($v['id']); ?>" value="<?php echo e($v['parent_id']); ?>" />
                                            <td style="font-size:14px;"><?php echo $v['level'] ? '╚'.str_repeat('════', $v['level']) : '╠'; ?></td>
                                            <td><input type="text" name="res_key@<?php echo e($v['id']); ?>" value="<?php echo e($v['res_key']); ?>" autocomplete="off" class="layui-input" /></td>
                                            <td>
                                                <select name="res_type@<?php echo e($v['id']); ?>">
                                                    <option value="string" <?php echo $v['req_type'] == 'string' ? 'selected' : ''; ?>>String</option>
                                                    <option value="int" <?php echo $v['req_type'] == 'int' ? 'selected' : ''; ?>>int</option>
                                                    <option value="float" <?php echo $v['req_type'] == 'float' ? 'selected' : ''; ?>>float</option>
                                                    <option value="boolean" <?php echo $v['req_type'] == 'boolean' ? 'selected' : ''; ?>>boolean</option>
                                                    <option value="array" <?php echo $v['req_type'] == 'array' ? 'selected' : ''; ?>>array</option>
                                                    <option value="object" <?php echo $v['req_type'] == 'object' ? 'selected' : ''; ?>>object</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="res_description@<?php echo e($v['id']); ?>" value="<?php echo e($v['res_description']); ?>" autocomplete="off" class="layui-input" /></td>
                                            <td><input type="number" name="res_sort@<?php echo e($v['id']); ?>" value="<?php echo e($v['res_sort']); ?>" autocomplete="off" class="layui-input" /></td>
                                            <td>
                                                <input type="button" value="추가" class="layui-btn" onclick="add_res_row(this, <?php echo $v['id']; ?>, <?php echo $v['level']; ?>)" />
                                                <input type="button" value="삭제" class="layui-btn layui-btn-danger" onclick="remove_res_row(this);" />
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="api_desc" class="layui-form-label">
                                    상세설명
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="api_desc" name="api_desc" class="layui-textarea"><?php echo e($field['api_desc']); ?></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="memo" class="layui-form-label">
                                    메모
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="memo" name="memo" class="layui-textarea"><?php echo e($field['memo']); ?></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="request_demo" class="layui-form-label">
                                    REQUEST DEMO
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="request_demo" name="request_demo"><?php echo $field['request_demo']; ?></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="response_demo" class="layui-form-label">
                                    RESPONSE DEMO
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="response_demo" name="response_demo"><?php echo $field['response_demo']; ?></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="sort" class="layui-form-label">
                                    순서배열
                                </label>
                                <div class="layui-input-inline">
                                    <input type="number" id="sort" value="<?php echo e($field['sort']); ?>" name="sort" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">
                                </label>
                                <input type="button" value="업데이트" class="layui-btn" onclick="edit();" />
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="<?php echo _url_('public/lib/ueditor/ueditor.config.js'); ?>"></script>
<script src="<?php echo _url_('public/lib/ueditor/ueditor.all.min.js'); ?>"></script>
<script src="<?php echo _url_('public/lib/ueditor/lang/en/en.js'); ?>"></script>
<script src="<?php echo _url_('public/lib/ueditor/button.config.js'); ?>"></script>
<script>
    UE.getEditor('request_demo', {
        "initialFrameWidth" : "100%",
        "initialFrameHeight" : 200,
        "maximumWords" : 50000,
        "toolbars" : btn_file
    });
    UE.getEditor('response_demo', {
        "initialFrameWidth" : "100%",
        "initialFrameHeight" : 200,
        "maximumWords" : 50000,
        "toolbars" : btn_file
    });

    layui.use(['form', 'layer']);

    let sending = 0;

    let req_index = parseInt('<?php echo e($max_req_id+1); ?>');
    function add_req_row(obj, index, level){
        let curIndex = index !== undefined ? index : 0;
        let curLevel = level !== undefined ? parseInt(level) + 1 : 1;

        let tabs;
        if (curLevel === 1) {
            tabs = '╠';
        } else {
            tabs = '╚' + String('════').repeat(parseInt(level));
        }

        let html = '\
        <tr class="index-'+curLevel+'">\
            <input type="hidden" name="req_parent@'+req_index+'" value="'+curIndex+'" />\
            <td style="font-size:14px;">'+tabs+'</td>\
            <td><input type="text" name="req_key@'+req_index+'" value="" autocomplete="off" class="layui-input" /></td>\
            <td>\
                <select name="req_type@'+req_index+'">\
                    <option value="string">String</option>\
                    <option value="int">int</option>\
                    <option value="float">float</option>\
                    <option value="boolean">boolean</option>\
                    <option value="array">array</option>\
                    <option value="object">object</option>\
                </select>\
            </td>\
            <td>\
                <select name="req_mode@'+req_index+'">\
                    <option value="1">필수</option>\
                    <option value="0">비필수</option>\
                </select>\
            </td>\
            <td><input type="text" name="req_description@'+req_index+'" value="" autocomplete="off" class="layui-input" /></td>\
            <td><input type="number" name="req_sort@'+req_index+'" value="9" autocomplete="off" class="layui-input" /></td>\
            <td>\
                <input type="button" value="추가" class="layui-btn" onclick="add_req_row(this, '+req_index+', '+curLevel+')" />\
                <input type="button" value="삭제" class="layui-btn layui-btn-danger" onclick="remove_req_row(this);" />\
            </td>\
        </tr>';

        if (obj) {
            $(obj).parents('tr').after(html);
        } else {
            $('table.req-table tbody').append(html);
        }

        req_index++;

        layui.form.render('select');
    }

    function remove_req_row(obj){
        $(obj).parents('tr').remove();
    }

    let res_index = parseInt('<?php echo e($max_res_id+1); ?>');
    function add_res_row(obj, index, level){
        let curIndex = index !== undefined ? index : 0;
        let curLevel = level !== undefined ? parseInt(level) + 1 : 1;

        let tabs;
        if (curLevel === 1) {
            tabs = '╠';
        } else {
            tabs = '╚' + String('════').repeat(parseInt(level));
        }

        let html = '\
        <tr class="index-'+curLevel+'">\
            <input type="hidden" name="res_parent@'+res_index+'" value="'+curIndex+'" />\
            <td style="font-size:14px;">'+tabs+'</td>\
            <td><input type="text" name="res_key@'+res_index+'" value="" autocomplete="off" class="layui-input" /></td>\
            <td>\
                <select name="res_type@'+res_index+'">\
                    <option value="string">String</option>\
                    <option value="int">int</option>\
                    <option value="float">float</option>\
                    <option value="boolean">boolean</option>\
                    <option value="array">array</option>\
                    <option value="object">object</option>\
                </select>\
            </td>\
            <td><input type="text" name="res_description@'+res_index+'" value="" autocomplete="off" class="layui-input" /></td>\
            <td><input type="number" name="res_sort@'+res_index+'" value="9" autocomplete="off" class="layui-input" /></td>\
            <td>\
                <input type="button" value="추가" class="layui-btn" onclick="add_res_row(this, '+res_index+', '+curLevel+')" />\
                <input type="button" value="삭제" class="layui-btn layui-btn-danger" onclick="remove_res_row(this);" />\
            </td>\
        </tr>';

        if (obj) {
            $(obj).parents('tr').after(html);
        } else {
            $('table.res-table tbody').append(html);
        }

        res_index++;

        layui.form.render('select');
    }

    function remove_res_row(obj){
        $(obj).parents('tr').remove();
    }

    function edit(){
        if (sending === 1) {
            return false;
        }
        sending = 1;

        let api_id = '<?php echo e($field['id']); ?>';
        let data = $('#data-form').serializeArray();

        $.post('<?php echo _url_('api/edit') ?>', {'id':api_id, 'data':data}, function(data){
            if (data.state === 0) {
                layer.msg(data.msg, {time:2000}, function(){
                    window.location.href = '<?php echo _url_('api/show') ?>?api_id=' + data.api_id;
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