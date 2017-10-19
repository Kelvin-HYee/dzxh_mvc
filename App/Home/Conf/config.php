<?php
return array(
	//'配置项'=>'配置值'
    'DB_PREFIX'             =>  'xh_',    // 数据库表前缀
    'URL_HTML_SUFFIX'       =>  'shtml',   //伪静态设置
    'DEFAULT_V_LAYER'       =>  'Template',   //默认视图层
    'URL_CASE_INSENSITIVE' =>true,
    //模板相关配置
    'TMPL_PARSE_STRING'     => array(
        '__js__'	 =>  __ROOT__.'/Public/js',
        '__images__' =>  __ROOT__.'/Public/images',
        '__css__'    => __ROOT__.'/Public/css',
        '__fonts__' =>__ROOT__.'/Public/fonts',
        '__plugins__'  => __ROOT__.'/Public/plugins',
        '__lefturl__' => __ROOT__.'/'.APP_NAME.'/Admin',),
);