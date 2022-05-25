<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

/*
挂钩点
以下是所有可用挂钩点的一份列表：

pre_system 在系统执行的早期调用，这个时候只有 基准测试类 和 钩子类 被加载了， 还没有执行到路由或其他的流程。
pre_controller 在你的控制器调用之前执行，所有的基础类都已加载，路由和安全检查也已经完成。
post_controller_constructor 在你的控制器实例化之后立即执行，控制器的任何方法都还尚未调用。
post_controller 在你的控制器完全运行结束时执行。
display_override 覆盖 _display() 方法，该方法用于在系统执行结束时向浏览器发送最终的页面结果。 这可以让你有自己的显示页面的方法。注意你可能需要使用 $this->CI =& get_instance() 方法来获取 CI 超级对象，以及使用 $this->CI->output->get_output() 方法来 获取最终的显示数据。
cache_override 使用你自己的方法来替代 输出类 中的 _display_cache() 方法，这让你有自己的缓存显示机制。
post_system 在最终的页面发送到浏览器之后、在系统的最后期被调用
*/

/*
$hook['pre_system'] = [
    'class'=>'MyClass',
    'function'=>'MyFunction',
    'filename'=>'MyClass.php',
    'filepath'=>'hooks',
    'params'=>array('beer', 'wine', 'snack'),
];
*/

$hook['post_controller_constructor'][] = [//管理员登陆验证
    'class'=>'AdminLogin',
    'function'=>'loginCheck',
    'filename'=>'AdminLogin.php',
    'filepath'=>'hooks',
    'params'=>array(),
];

$hook['post_controller_constructor'][] = [//管理员权限验证
    'class'=>'Privilege',
    'function'=>'guard',
    'filename'=>'Privilege.php',
    'filepath'=>'hooks',
    'params'=>array(),
];

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */