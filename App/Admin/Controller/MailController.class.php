<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 15:20
 */

namespace Admin\Controller;
use Think\Controller;
class MailController extends CommonController{
    public function index(){
        $count=M('mail')->count('mail');
        $this->assign('count',$count);
        $this->display();
    }
    
    public function add(){
        $mail_list=M('mail')->field('mail')->select();
        $i=0;//发送成功数
        $s=0;//发送失败数
       foreach($mail_list as $value){
            foreach ($value as $v){
        if(sendMail($v,$_POST['title'],$_POST['content'])){
            $i++;
        }else{
            $s++;
        }
            }
        }
        $data = array(
            'pid' => $_SESSION['adminId'],
            'username'=>$_SESSION['adminName'],
            'ip' => get_client_ip(),
            'last_time' => date('y-m-d H:i:s'),
            'handle' =>5,
            'handle_o'=>'发送了订阅邮件！成功数：'.$i.' 失败数:'.$s
        );
        M('monitor') -> add($data);
        $this->success("发送成功数：".$i."<br/>发送失败数:".$s, $this->site_url, 10);
    }

}