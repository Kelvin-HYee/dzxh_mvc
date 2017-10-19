<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>数据库处理页面</title>
    <style>
        body {
            margin: 0px;
            padding: 0px;
            font-family: "微软雅黑";
            background-color: #003366;
            color: #736F6F;
            font-size: 13px;
        }
        .box {
            width: 800px;
            height: 420px;
            margin: 0 auto;
            background-image: url(img/xh_logo.png);
            top: 150px;
            position: relative;
            border-radius: 8px;
            background-repeat: no-repeat;
            background-position: left;
            background-position-x: 60px;;
            background-color: #FFFFFF;
        }
        .loginbox {
            height: 200px;
            width: 400px;
            position: absolute;
            right: 20px;
            top: 50px;
            line-height: 30px;
        }
        .alert {
            height: 20px;
            width: 400px;
            position: relative;
            left: 350px;
            top: 360px;
            color: #FF0004;
            line-height: 30px;
        }
        .text {
            width: 200px;
            height: 25px;
            border-radius: 5px;
            margin: 10px;
            border: 1px solid #D3D3D3;
        }
        .btn {
            width: 100px;
            height: 30px;
            background-color: #003366;
            border-radius: 8px;
            color: #FFFFFF;
        }
    </style>
</head>
<body>
<div class="box">
    <div class="loginbox">
        <?php
        @header("content-Type: text/html; charset=utf-8");
        $host = trim($_POST['DB_HOST']).':'.trim($_POST['DB_PORT']);
        $user = trim($_POST['DB_USER']);
        $pwd = trim($_POST['DB_PWD']);
        $data_base = trim($_POST['DB_NAME']);
        $conn = @mysql_connect($host,$user,$pwd);
        if (!$conn)
        {
            die('数据库链接失败,请返回');
        }
        $db_crac=@mysql_query("CREATE DATABASE IF NOT EXISTS $data_base default charset utf8 COLLATE utf8_general_ci;",$conn);
        if (!$db_crac){
            die('数据库创建失败.请手动创建数据库');
        }
        $db_selected=@mysql_select_db($data_base,$conn);
        if(!$db_selected){
            die('数据库创建失败.请手动创建数据库');
        }
        $file_name ="dzxh.sql";
        $get_sql_data = file_get_contents($file_name);
        $explode = explode(";",$get_sql_data);
        $cnt = count($explode);
        for($i=0;$i<$cnt ;$i++){
            $sql = trim($explode[$i]);
            mysql_query('SET NAMES UTF8');
            @$result =mysql_query($sql);
        }
        mysql_close($conn);
        $peizhi="<?php 
			 return array(
			'URL_MODEL' =>  2, 
			'DB_TYPE'=>  'mysql', 
			'DB_HOST'=>"."'".trim($_POST['DB_HOST'])."'".", 
			'DB_PORT'=>"."'".trim($_POST['DB_PORT'])."'".", 
			'DB_NAME'=>"."'".trim($_POST['DB_NAME'])."'".", 
			'DB_USER'=>"."'".trim($_POST['DB_USER'])."'".", 
			'DB_PWD'=>"."'".trim($_POST['DB_PWD'])."'".", 	
			);?>";
        $peiz="<?php
        return array(
            'MAIL_HOST'=>"."'".trim($_POST['MAIL_HOST'])."'".",
			'MAIL_USERNAME'=>"."'".trim($_POST['MAIL_USERNAME'])."'".",
			'MAIL_FROM'=>"."'".trim($_POST['MAIL_FROM'])."'".",
			'MAIL_FROMNAME'=>"."'".trim($_POST['MAIL_FROMNAME'])."'".",
			'MAIL_PASSWORD'=>"."'".trim($_POST['MAIL_PASSWORD'])."'".",
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
        );?>";
        $confpath ='../App/Common/Conf/config.php';
        $webconfig = @fopen($confpath,w);

        $confpa ='../App/Admin/Conf/config.php';
        $weconfig = @fopen($confpa,w);

        $lockfile = @fopen("../check.php",w);
        if($webconfig){
            $fwrite=fwrite($webconfig,$peizhi );
            if($fwrite > 0){
                echo "<p>配置文件1创建成功！</p>";
            }
        }
        if($weconfig){
            $fwrite=fwrite($weconfig,$peiz );
            if($fwrite > 0){
                echo "<p>配置文件2创建成功！</p>";
                echo "<p>程序已成功安装！</p>";
                echo "<a href=../Admin>进入后台进行设置</a>";
            }
        }else{
            echo "配置文件生成失败，请检查根目录是否可写！";
        }
        ?>

    </div>
</div>
</body>
</html>
