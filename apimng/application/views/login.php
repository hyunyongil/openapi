<!doctype html>
<html class="x-admin-sm" lang="ko">
<head>
    <?php echo $this->load->view('header'); ?>
</head>
    <body>

        <div class="login layui-anim layui-anim-up">
            <div class="message">도매매API관리</div>
            <div id="darkbannerwrap"></div>

            <form class="layui-form" id="data-form">
                <p class="input-wrap">
                    <input name="username" placeholder="아이디"  type="text" class="layui-input" autocomplete="off" />
                </p>
                <hr class="hr15">
                <p class="input-wrap">
                    <input name="password" placeholder="비밀번호"  type="password" class="layui-input" autocomplete="off" />
                </p>
                <hr class="hr15">
                <p class="input-wrap">
                    <input name="code" placeholder="인증코드"  type="text" class="layui-input" autocomplete="off" />
                    <img src="<?php echo _url_('login/code'); ?>" id="code-view" onclick="getCode();" />
                </p>
                <hr class="hr15">
                <input value="로그인" type="button" onclick="login(this);" class="admin-user-login" />
                <hr class="hr20" >
            </form>
        </div>

        <script>
            let sending = 0;
            layui.use(['layer']);

            $(function(){
                $(document).keyup(function(event){
                    if(event.keyCode === 13){
                        login();
                    }
                });
            });

            function getCode(){
                $('#code-view').attr('src', '<?php echo _url_('login/code'); ?>?'+Math.random());
            }

            function login(){
                if (sending === 1) {
                    return false;
                }
                sending = 1;

                let data = $('#data-form').serializeArray();

                $.post('<?php echo _url_('login/loginAction'); ?>', {'data':data}, function(data){
                    if (data.state === 0) {
                        window.location.href = '<?php echo _url_('/') ?>';
                    } else {
                        layer.msg(data.msg, {time:2000,anim:6});
                        sending = 0;
                        getCode();
                    }
                });
            }
        </script>

    </body>
</html>