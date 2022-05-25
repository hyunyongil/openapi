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
                <div class="layui-card-body ">
                    <blockquote class="layui-elem-quote">
                        <span class="x-red"><?php echo $admin['username']; ?></span>
                        <br/>
                        <span><?php echo date('Y-m-d H:i:s'); ?></span>
                    </blockquote>
                </div>
            </div>
        </div>

        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">System Information</div>
                <div class="layui-card-body ">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <th>Server Host</th>
                            <td><?php echo $_SERVER['HTTP_HOST']; ?></td></tr>
                        <tr>
                            <th>Oparating System</th>
                            <td><?php echo php_uname(); ?></td></tr>
                        <tr>
                            <th>Web Server</th>
                            <td><?php echo php_sapi_name(); ?></td></tr>
                        <tr>
                            <th>PHP Version</th>
                            <td><?php echo PHP_VERSION; ?></td></tr>
                        <tr>
                            <th>Server IP</th>
                            <td><?php echo GetHostByName($_SERVER['SERVER_NAME']); ?></td></tr>
                        <tr>
                            <th>MYSQL Version</th>
                            <td><?php echo $mysql['version']; ?></td></tr>
                        <tr>
                            <th>Upload Max Size</th>
                            <td><?php echo get_cfg_var ("upload_max_filesize") ? get_cfg_var ("upload_max_filesize") : "Permission denied"; ?></td></tr>
                        <tr>
                            <th>Timezone</th>
                            <td><?php echo date_default_timezone_get(); ?></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>