<!doctype html>
<html class="x-admin-sm" lang="ko">
<head>
    <?php echo $this->load->view('header'); ?>
</head>
<body>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">

        <div class="layui-col-md12">
            <div class="layui-card view-box">
                <h2 class="view-head">
                    <strong><?php echo e($apiData['api_name']); ?></strong>

                    <?php if($apiData['status']): ?>
                    <span class="publish">Publish</span>
                    <?php else: ?>
                    <span class="test">Test</span>
                    <?php endif; ?>

                    <?php if($apiData['with_token']): ?>
                    <span class="token">token</span>
                    <?php endif; ?>
                </h2>

                <div class="divide20"></div>
                <div class="view-version">
                    <?php foreach($apiGroup as $v): ?>
                    <a href="<?php echo _url_('api/view?api_id='.$v['id']); ?>" class="<?php echo $v['id'] == $apiData['id'] ? 'active' : '' ?>">v<?php echo e($v['version']); ?></a>
                    <?php endforeach; ?>
                </div>

                <div class="divide20"></div>
                <div class="view-subinfo">
                    <p>creator: <?php echo e($creator['username']) ?> <?php echo e(date('Y-m-d', $apiData['create_time'])); ?></p>
                    <?php if($lastEditor): ?>
                    <p>last editor: <?php echo e($lastEditor['username']) ?> <?php echo e(date('Y-m-d', $apiData['last_edit_time'])); ?></p>
                    <?php endif; ?>
                </div>

                <?php if($apiData['api_desc']): ?>
                <div class="divide20"></div>
                <div class="view-title">Description</div>
                <div class="view-content"><?php echo e($apiData['api_desc']); ?></div>
                <?php endif; ?>

                <div class="divide40"></div>
                <div class="view-title">Host</div>
                <div class="view-content"><?php echo e($project['host']); ?></div>

                <div class="divide40"></div>
                <div class="view-title">Path</div>
                <div class="view-content"><?php echo e($apiData['api_path']); ?></div>

                <div class="divide40"></div>
                <div class="view-title">Method</div>
                <ul class="view-method">
                    <li><?php echo e($apiData['api_method']); ?></li>
                </ul>

                <div class="divide40"></div>
                <div class="view-title">Request Parameters</div>
                <div class="layui-table-body layui-table-main">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($reqParam as $v): ?>
                        <tr>
                            <td>
                                <?php echo $v['level'] ? '└' . str_repeat('╌╌', $v['level']) : ''; ?>
                                <?php echo e($v['req_key']); ?>
                            </td>
                            <td><?php echo e($v['req_type']); ?></td>
                            <td><?php echo $v['req_mode'] ? 'Required' : '-'; ?></td>
                            <td><?php echo e($v['req_description']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="divide40"></div>
                <div class="view-title">Response Parameters</div>
                <div class="layui-table-body layui-table-main">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($resParam as $v): ?>
                        <tr>
                            <td>
                                <?php echo $v['level'] ? '└' . str_repeat('╌╌', $v['level']) : ''; ?>
                                <?php echo e($v['res_key']); ?>
                            </td>
                            <td><?php echo e($v['res_type']); ?></td>
                            <td><?php echo e($v['res_description']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="divide40"></div>
                <div class="view-title">Request Example</div>
                <div class="view-richtext"><?php echo $apiData['request_demo']; ?></div>

                <div class="divide40"></div>
                <div class="view-title">Response Example</div>
                <div class="view-richtext"><?php echo $apiData['response_demo']; ?></div>

                <div class="divide40"></div>
                <div class="view-title">Errors</div>
                <div class="layui-table-body layui-table-main">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>State</th>
                            <th>Code</th>
                            <th>Message</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($errors as $v): ?>
                        <tr>
                            <td><?php echo e($v['state']); ?></td>
                            <td><?php echo e($v['code']); ?></td>
                            <td><?php echo e($v['message']); ?></td>
                            <td><?php echo e($v['description']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($apiData['memo']): ?>
                <div class="divide40"></div>
                <div class="view-title">Memo</div>
                <div class="view-content"><?php echo e($apiData['memo']); ?></div>
                <?php endif; ?>

                <?php if($admin['privilege'] == 1 || $admin['privilege'] == 2): ?>
                <div class="divide50"></div>
                <div class="divide50"></div>
                <div class="opt-btns">
                    <a href="javascript:void(0);" onclick="destroy(this,'<?php echo $apiData['id']; ?>')" class="delete">Delete</a>
                    <a href="<?php echo _url_('api/edit_view?api_id='.$apiData['id']); ?>" class="update">Update</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
    function destroy(obj,id){
        layer.confirm('삭제하시겠습니까?',function(){
            $.post('<?php echo _url_('api/del'); ?>', {'id': id}, function(data){
                if (data.state === 0) {
                    layer.msg(data.msg, {time:2000}, function(){
                        window.location.href = '<?php echo _url_('/'); ?>';
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