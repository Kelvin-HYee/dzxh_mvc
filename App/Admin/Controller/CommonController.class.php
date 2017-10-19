<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/5
 * Time: 14:40
 */

namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller
{
    public function _initialize()
    {
        if (!isset($_SESSION['adminId'])) {
            $this->error('请先登录！！',U('Index/login'),5);
        } elseif (!isset($_COOKIE['adminId'])){
            $this->error('登录信息已过期!请重新登录',U('Index/login'),5);
        }elseif (M('admin')->where("username='".$_COOKIE['adminName']."'")->select() == null) {
            $this->error('你的登陆信息不正常!请重新登录',U('Index/login'),5);
         }
    }
}