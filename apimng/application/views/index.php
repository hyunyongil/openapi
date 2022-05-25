<!doctype html>
<html class="x-admin-sm" lang="ko">
<head>
    <?php echo $this->load->view('header'); ?>
    <script>
        if(window.top !== window){
            window.top.location.href = document.location.href;
        }
    </script>
</head>
<body>

<div class="container">
    <div class="logo">
        <a href="<?php echo _url_('/') ?>">도매매API관리</a></div>
    <div class="left_open">
        <a><i title="메뉴보기" class="iconfont">&#xe699;</i></a>
    </div>

    <?php if(count($projects)): ?>
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"><?php echo $curProject ? $curProject['pj_name'] : '프로젝트'; ?></a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <?php foreach($projects as $v): ?>
                <dd>
                    <a href="javascript:void(0);" onclick="setProject('<?php echo $v['id']; ?>');">
                        <i class="iconfont">&#xe6cb;</i><?php echo $v['pj_name']; ?></a></dd>
                <?php endforeach; ?>
            </dl>
        </li>
    </ul>
    <?php endif; ?>

    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:void(0);"><?php echo $admin['username']; ?></a>
        </li>
        <li class="layui-nav-item to-index">
            <a href="<?php echo _url_('login/logout') ?>">로그아웃</a></li>
    </ul>
</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div class="side-wrap">
        <div id="side-nav">
            <ul id="nav">
                <?php if($admin['privilege'] == '1'): ?>
                <li>
                    <a onclick="xadmin.add_tab('관리자', '<?php echo _url_('admin'); ?>')">
                        <i class="iconfont left-nav-li" lay-tips="관리자">&#xe6b8;</i>
                        <cite>관리자</cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                </li>
                <li>
                    <a onclick="xadmin.add_tab('프로젝트', '<?php echo _url_('project'); ?>')">
                        <i class="iconfont left-nav-li" lay-tips="프로젝트">&#xe6a2;</i>
                        <cite>프로젝트</cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                </li>
                <?php endif; ?>

                <?php foreach($apiList as $k=>$v): ?>
                <li>
                    <a href="javascript:;">
                        <i class="iconfont left-nav-li" lay-tips="<?php echo e($k); ?>">&#xe6f2;</i>
                        <cite><?php echo e($k); ?></cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                    <ul class="sub-menu">
                        <?php foreach($v as $v1): ?>
                        <li>
                            <a onclick="xadmin.add_tab('<?php echo $v1['api_name']; ?>','<?php echo _url_('api/show?api_id='.$v1['id']); ?>')">
                                <i class="iconfont">&#xe6a7;</i>
                                <cite><?php echo $v1['api_name']; ?></cite></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endforeach; ?>

                <?php if($curProject && ($admin['privilege'] == '1' || $admin['privilege'] == '2')): ?>
                <li>
                    <a onclick="xadmin.add_tab('API ERROR', '<?php echo _url_('error'); ?>')">
                        <i class="iconfont left-nav-li" lay-tips="API ERROR">&#xe6b6;</i>
                        <cite>API ERROR</cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                </li>
                <li>
                    <a onclick="xadmin.add_tab('API추가', '<?php echo _url_('api/create_view'); ?>')">
                        <i class="iconfont left-nav-li" lay-tips="API추가">&#xe6b9;</i>
                        <cite>API추가</cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                </li>
                <?php endif; ?>

                <?php if($admin['privilege'] == '1'): ?>
                <li>
                    <a onclick="xadmin.add_tab('아이콘', '<?php echo _url_('main/icon'); ?>')">
                        <i class="iconfont left-nav-li" lay-tips="아이콘">&#xe6a0;</i>
                        <cite>아이콘</cite>
                        <i class="iconfont nav_right">&#xe697;</i></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>Home</li></ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">Close Current</dd>
                <dd data-type="other">Close Other</dd>
                <dd data-type="all">Close All</dd></dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src="<?php echo _url_('main/mainView'); ?>" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->

<script>
    function setProject(id){
        $.post('<?php echo _url_('main/set_pj'); ?>', {'id':id}, function(data){
            window.location.reload();
        });
    }
</script>

</body>
</html>
