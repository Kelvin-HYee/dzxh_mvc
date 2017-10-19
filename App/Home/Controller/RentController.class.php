<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:10
 */

namespace Home\Controller;
use Think\Controller;
class RentController extends Controller {
    public function index(){
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(3)->select();
        $qq=M('qq')->where('id=1')->select();
        $server=M('admin_mes')->field('cate')->where('id!=1')->select();
        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('icp',$icp);
        $this->assign('server',$server);
        $this->assign('qq',$qq);
        $this->assign('most',$most);
        $this->display();
    }
}