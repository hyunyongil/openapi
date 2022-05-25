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
                                <label for="copydata" class="layui-form-label">
                                    API copy</label>
                                <div class="layui-input-inline">
                                    <select lay-filter="module_id">
                                        <option value="0">=모듈선택=</option>
                                        <?php foreach($modules as $v): ?>
                                        <option value="<?php echo e($v['id']); ?>"><?php echo e($v['mo_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="layui-input-inline">
                                    <select lay-filter="api_id" name="_api_id">
                                        <option value="0">=api선택=</option>
                                    </select>
                                </div>
                                <div class="layui-input-inline">
                                    <input type="button" value="COPY" class="layui-btn" onclick="api_copy();" />
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="api_name" class="layui-form-label">
                                    <span class="x-red">*</span>API명</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="api_name" name="api_name" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label for="api_path" class="layui-form-label">
                                    <span class="x-red">*</span>PATH</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="api_path" name="api_path" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label for="api_method" class="layui-form-label">
                                    <span class="x-red">*</span>METHOD</label>
                                <div class="layui-input-inline">
                                    <select name="api_method">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="with_token" class="layui-form-label">
                                    <span class="x-red">*</span>TOKEN</label>
                                <div class="layui-input-inline">
                                    <select name="with_token">
                                        <option value="1">필수</option>
                                        <option value="0">비필수</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="version" class="layui-form-label">
                                    API버전</label>
                                <div class="layui-input-inline">
                                    <input type="number" id="version" name="version" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label for="module_name" class="layui-form-label">
                                    <span class="x-red">*</span>모듈</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="module_name" list="modules" name="module_name" required="" lay-verify="required" autocomplete="off" class="layui-input">
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
                                        <option value="0">테스트</option>
                                        <option value="1">사용중</option>
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
                                        <tbody></tbody>
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
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="api_desc" class="layui-form-label">
                                    상세설명
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="api_desc" name="api_desc" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="memo" class="layui-form-label">
                                    메모
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="memo" name="memo" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="request_demo" class="layui-form-label">
                                    REQUEST DEMO
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="request_demo" name="request_demo"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label for="response_demo" class="layui-form-label">
                                    RESPONSE DEMO
                                </label>
                                <div class="layui-input-block">
                                    <textarea id="response_demo" name="response_demo"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="sort" class="layui-form-label">
                                    순서배열
                                </label>
                                <div class="layui-input-inline">
                                    <input type="number" id="sort" value="9" name="sort" autocomplete="off" class="layui-input"></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">
                                </label>
                                <input type="button" value="추가하기" class="layui-btn" onclick="add();" />
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

                $('select[name=_api_id]').html(html);
                form.render('select');
            });
        });
    });

    let sending = 0;

    function api_copy(){
        var api_id = parseInt($('select[name=_api_id]').val());

        if (api_id === 0) return false;

        $.get('<?php echo _url_('api/get_copy_api'); ?>?api_id='+api_id, function(data){
            $('input[name=api_name]').val(data.api.api_name);
            $('input[name=api_path]').val(data.api.api_path);
            $('select[name=api_method]').find('option[value='+data.api.api_method+']').attr("selected",true);
            $('select[name=with_token]').find('option[value='+data.api.with_token+']').attr("selected",true);
            $('input[name=version]').val(data.api.version);
            $('input[name=module_name]').val(data.api.mo_name);
            $('select[name=status]').find('option[value='+data.api.status+']').attr("selected",true);
            $('textarea[name=api_desc]').val(data.api.api_desc);
            $('input[name=sort]').val(data.api.sort);
            $('textarea[name=memo]').val(data.api.memo);
            $('textarea[name=request_demo]').val(data.api.request_demo);
            $('textarea[name=response_demo]').val(data.api.response_demo);
            $('#ueditor_0').contents().find('body').html(data.api.request_demo);
            $('#ueditor_1').contents().find('body').html(data.api.response_demo);

            let req_html = '';
            let res_html = '';

            $(data.req_param).each(function(k, v){
                var curLevel = parseInt(v.level);

                var tabs;
                if (curLevel === 0) {
                    tabs = '╠';
                } else {
                    tabs = '╚' + String('════').repeat(curLevel);
                }

                req_html += '\
                <tr class="index-'+v.id+'">\
                    <input type="hidden" name="req_parent@'+v.id+'" value="'+v.parent_id+'" />\
                    <td style="font-size:14px;">'+tabs+'</td>\
                    <td><input type="text" name="req_key@'+v.id+'" value="'+v.req_key+'" autocomplete="off" class="layui-input" /></td>\
                    <td>\
                        <select name="req_type@'+v.id+'">\
                            <option value="string" '+(v.req_type === 'string' ? 'selected' : '')+'>String</option>\
                            <option value="int" '+(v.req_type === 'int' ? 'selected' : '')+'>int</option>\
                            <option value="float" '+(v.req_type === 'float' ? 'selected' : '')+'>float</option>\
                            <option value="boolean" '+(v.req_type === 'boolean' ? 'selected' : '')+'>boolean</option>\
                            <option value="array" '+(v.req_type === 'array' ? 'selected' : '')+'>array</option>\
                            <option value="object" '+(v.req_type === 'object' ? 'selected' : '')+'>object</option>\
                        </select>\
                    </td>\
                    <td>\
                        <select name="req_mode@'+v.id+'">\
                            <option value="1" '+(v.req_mode === '1' ? 'selected' : '')+'>필수</option>\
                            <option value="0" '+(v.req_mode === '0' ? 'selected' : '')+'>비필수</option>\
                        </select>\
                    </td>\
                    <td><input type="text" name="req_description@'+v.id+'" value="'+v.req_description+'" autocomplete="off" class="layui-input" /></td>\
                    <td><input type="number" name="req_sort@'+v.id+'" value="'+v.req_sort+'" autocomplete="off" class="layui-input" /></td>\
                    <td>\
                        <input type="button" value="추가" class="layui-btn" onclick="add_req_row(this, '+v.id+', '+(curLevel+1)+')" />\
                        <input type="button" value="삭제" class="layui-btn layui-btn-danger" onclick="remove_req_row(this);" />\
                    </td>\
                </tr>';
            });

            $(data.res_param).each(function(k, v){
                var curLevel = parseInt(v.level);

                var tabs;
                if (curLevel === 0) {
                    tabs = '╠';
                } else {
                    tabs = '╚' + String('════').repeat(curLevel);
                }

                res_html += '\
                <tr class="index-'+curLevel+'">\
                    <input type="hidden" name="res_parent@'+v.id+'" value="'+v.parent_id+'" />\
                    <td style="font-size:14px;">'+tabs+'</td>\
                    <td><input type="text" name="res_key@'+v.id+'" value="'+v.res_key+'" autocomplete="off" class="layui-input" /></td>\
                    <td>\
                        <select name="res_type@'+v.id+'">\
                            <option value="string" '+(v.res_type === 'string' ? 'selected' : '')+'>String</option>\
                            <option value="int" '+(v.res_type === 'int' ? 'selected' : '')+'>int</option>\
                            <option value="float" '+(v.res_type === 'float' ? 'selected' : '')+'>float</option>\
                            <option value="boolean" '+(v.res_type === 'boolean' ? 'selected' : '')+'>boolean</option>\
                            <option value="array" '+(v.res_type === 'array' ? 'selected' : '')+'>array</option>\
                            <option value="object" '+(v.res_type === 'object' ? 'selected' : '')+'>object</option>\
                        </select>\
                    </td>\
                    <td><input type="text" name="res_description@'+v.id+'" value="'+v.res_description+'" autocomplete="off" class="layui-input" /></td>\
                    <td><input type="number" name="res_sort@'+v.id+'" value="'+v.res_sort+'" autocomplete="off" class="layui-input" /></td>\
                    <td>\
                        <input type="button" value="추가" class="layui-btn" onclick="add_res_row(this, '+v.id+', '+curLevel+')" />\
                        <input type="button" value="삭제" class="layui-btn layui-btn-danger" onclick="remove_res_row(this);" />\
                    </td>\
                </tr>';
            });

            $('table.req-table tbody').append(req_html);
            $('table.res-table tbody').append(res_html);

            form.render('select');
        });
    }

    let req_index = 1;
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

    let res_index = 1;
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

    function add(){
        if (sending === 1) {
            return false;
        }
        sending = 1;

        let data = $('#data-form').serializeArray();

        $.post('<?php echo _url_('api/create') ?>', {'data':data}, function(data){
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