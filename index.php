<?php
if(!file_exists('check.php')){
    header("Content-type: text/html; charset=utf-8");
    header("location:DbSet/");
    exit();
}else{
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
//define('APP_DEBUG',True);
//绑定模块
//define('BIND_MODULE','Admin');
//关闭目录安全文件的生成
//define('BUILD_DIR_SECURE', false);
define('APP_NAME','App');
// 定义应用目录
define('APP_PATH','./App/');
// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
}