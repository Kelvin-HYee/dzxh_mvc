
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>数据库界面</title>
    <style>
        body {
            margin: 0px;
            padding: 0px;
            font-family: "微软雅黑";
            background-color: #660311;
            color: #736F6F;
            font-size: 13px;
        }
        .box {
            width: 800px;
            height: 700px;
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
        if (file_exists('../check.php')) {
            header("Content-type: text/html; charset=utf-8");
            echo "你已经安装过了！要重装请删除Conf目录下的check.php";
            exit();
        }
        ?>
        <form method="post" name="form1" action="doAction.php">
            <hr/>以下信息为php数据库配置信息,请认真填写!且不可再更改<hr/>
            数据库地址：
            <input type="text"  class="text" name="DB_HOST" value="localhost">
            <br/>
            数据库端口：
            <input type="text"  class="text" name="DB_PORT" value="3306">
            <br/>
            数据库名称：
            <input type="text" class="text" name="DB_NAME">
            <br/>
            数据库帐号：
            <input type="text"  class="text" name="DB_USER">
            <br/>
            数据库密码：
            <input type="text"  class="text" name="DB_PWD">

            <hr/>以下信息将被用来给订阅者发送邮件,请认真填写!且不可再更改<br/>推荐使用qq邮箱,因为其他没测试！！<hr/>

            smtp名称：
            <input type="text"  class="text" name="MAIL_HOST" value="smtp.qq.com" placeholder="smtp邮箱服务器的名称">
            <br/>
            邮箱名字：
            <input type="text"  class="text" name="MAIL_USERNAME" placeholder="1375643047@qq.com">
            <br/>
            邮箱地址：
            <input type="text" class="text" name="MAIL_FROM" placeholder="1375643047@qq.com">
            <br/>
            授权码：
            <input type="text"  class="text" name="MAIL_PASSWORD" placeholder="注意！不是邮箱密码！是授权密码">
            <br/>
            发件人姓名：
            <input type="text"  class="text" name="MAIL_FROMNAME" value="电子应用技术协会">
            <br/>
            <input type="submit" value="安装" class="btn">

        </form>
    </div>
</div>
</body>
</html>
