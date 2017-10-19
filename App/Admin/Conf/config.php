<?php
        return array(
            'MAIL_HOST'=>'smtp.qq.com',
			'MAIL_USERNAME'=>'1375643047@qq.com',
			'MAIL_FROM'=>'1375643047@qq.com',
			'MAIL_FROMNAME'=>'电子应用技术协会',
			'MAIL_PASSWORD'=>'',
			'MAIL_SMTPAUTH'=> TRUE,
			'MAIL_CHARSET'=>'utf-8',
			'MAIL_ISHTML'=>TRUE,
			//'配置项'=>'配置值'
    'DB_PREFIX'             =>  'xh_',    // 数据库表前缀
    'URL_HTML_SUFFIX'       =>  'shtml',   //伪静态设置
	'URL_CASE_INSENSITIVE' =>true,    //模板相关配置
    'TMPL_PARSE_STRING'     => array(
	'__js__'	 =>  __ROOT__.'/Public/js',
	'__img__' =>  __ROOT__.'/Public/image_icon',
	'__css__'    => __ROOT__.'/Public/css',
    '__plugins__'  => __ROOT__.'/Public/plugins',
	'__lefturl__' => __ROOT__.'/'.APP_NAME.'/Admin',),
        );?>