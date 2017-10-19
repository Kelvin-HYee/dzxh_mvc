<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:12
 */

namespace Home\Controller;
use Think\Controller;
class StarController extends Controller {
    public function index(){
        $tou=M('article')->where('cId=3')->order('id desc')->limit(1)->select();
        $star=M('article')->where('cId=3')->order('id desc')->limit(3)->select();
        $inform=M('Article')->field('id,title,author')->order('id desc')->where('cId=1')->limit(4)->select();
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(3)->select();
        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('icp',$icp);
        $this->assign('most',$most);
        $this->assign('inform',$inform);
        $this->assign('star',$star);
        $this->assign('tou',$tou);
        $this->display();
    }
}