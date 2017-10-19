<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:08
 */

namespace Home\Controller;
use Think\Controller;
class MovieController extends Controller {
    public function index(){
        $movie_photo=M('Article')->field('photo,id')->where('cId=2')->order('id desc')->limit(4)->select();
        $inform=M('Article')->field('id,title,author')->order('id desc')->where('cId=1')->limit(4)->select();
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(3)->select();
        $movie=M('Article')->field('id,title,content,photo')->order('id desc')->where('cId=2')->limit(5)->select();
        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('icp',$icp);
        $this->assign('movie',$movie);
        $this->assign('most',$most);
        $this->assign('inform',$inform);
        $this->assign('photo',$movie_photo);
        $this->display();
    }
    public function movie(){
        //电影反馈处理上传
        if(!IS_POST) halt('页面错误');
        $verify = I('post.ver');
        if(!check_verify($verify)){
            $this->error("亲，验证码输错了哦！",$this->site_url,5);
        }else{
            if(I('post.m_name')==null){
                $this->error('亲,你居然忘了输入电影名字！',$this->site_url,5);
            }elseif (I('post.p_name')==null){
                $this->error('亲,主角名字没有输入哦！',$this->site_url,5);
                }else{
                   $data=array(
                      'm_name' =>I('post.m_name'),
                      'p_name' =>I('post.p_name'),
                      'reason' =>I('post.reason'),
                      'time' =>date('Y-m-d H:i:s')
                  );
                  if(M('movie')->add($data)){
                      $this->success("谢谢您的电影反馈！我们已经收到！", $this->site_url, 5);
                  }
            }
        }
    }
}