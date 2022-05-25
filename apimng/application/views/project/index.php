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
              <cite>프로젝트</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="새로고침">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <button class="layui-btn" onclick="xadmin.open('프로젝트추가','<?php echo _url_('project/create_view'); ?>')"><i class="layui-icon"></i>추가하기</button>
                </div>
                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th width="80">Operation</th>
                            <th>프로젝트명</th>
                            <th>Host</th>
                            <th>상세설명</th>
                            <th>사용여부</th>
                            <th>생성시간</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($datas as $v): ?>
                        <tr>
                            <td class="td-manage">
                                <a title="모듈리스트"  onclick="xadmin.open('모듈리스트','<?php echo _url_('project/module_list?id='.$v['id']); ?>', 400)" href="javascript:;">
                                    <i class="iconfont">&#xe70c;</i>
                                </a>
                                <a title="수정하기"  onclick="xadmin.open('수정하기','<?php echo _url_('project/edit_view?id='.$v['id']); ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="삭제" onclick="destroy(this,'<?php echo $v['id']; ?>')" href="javascript:void(0);">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                            </td>
                            <td><?php echo e($v['pj_name']); ?></td>
                            <td><?php echo e($v['host']); ?></td>
                            <td><?php echo e($v['description']); ?></td>
                            <td class="td-status is_use">
                                <span class="layui-btn layui-btn-normal layui-btn-mini"><?php echo $v['is_use'] ? '사용함' : '사용안함'; ?></span></td>
                            <td><?php echo date('Y-m-d H:i', $v['addtime']); ?></td>

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
            $.post('<?php echo _url_('project/del'); ?>', {'id': id}, function(data){
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
