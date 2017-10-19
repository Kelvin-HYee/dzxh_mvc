<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:19
 */

namespace Home\Controller;
use Think\Controller;
class MemberController extends Controller {
    public function index(){
        $ip = get_client_ip();
        $Ip = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        $area = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
        $ipp='当前Ip:'.$area['country'].$area['area'];
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(3)->select();
        $qq=M('qq')->where('id=1')->select();
        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('ip',$ipp);
        $this->assign('icp',$icp);
        $this->assign('qq',$qq);
        $this->assign('most',$most);
        $this->display();
    }
    public function baom(){
        if(!IS_POST) halt('页面错误');
        $verify = I('post.ver');
        $phone=I('post.ph');
        $qq=I('post.qq');
        if(!check_verify($verify)){
            $this->error("亲，验证码输错了哦！",$this->site_url,5);
        }else {
            $check=M('join')->select();
            foreach ($check as $ch) {
                if (array_search($phone, $ch) or array_search($qq, $ch)) {
                    $this->error("亲，你已经报名了哦！", $this->site_url, 5);
                }
            }
            $data=array(
                'phone' =>I('post.ph'),
                'name'  =>I('post.name'),
                'xy'    =>I('post.xy'),
                'class' =>I('post.class'),
                'sex'   =>I('post.sex'),
                'qq'    =>I('post.qq'),
                'time'  =>date('y-m-d H:i:s',time()),
            );
            if (M('join')->add($data)){
                $this->success('我们已经收到了你的信息,请等待...',$this->site_url,5);
            }
        }
    }
}