<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller{
    /*后台首页*/
    public function index(){
        if ($_SESSION['adminId']==null && $_COOKIE['adminId']==null){
            header('Content-type:text/html;charset=utf-8');
            $this->error('请先登录！！',U('Index/login'),5);
        }elseif (M('admin')->where('id='.$_SESSION['adminId'])->count()==0){
            header('Content-type:text/html;charset=utf-8');
            $this->error('信息异常!请重新登录',U('Index/login'),5);
        }else{
            $movie=M('movie')->field('time')->select();
            $join=M('join')->field('time')->select();
            $i=0;
            $s=0;
            foreach ($movie as $mov){
                foreach ($mov as $mo){
                    if (date('Y-m-d')==substr($mo,0,10)){
                        $i++;
                    }
                }
            }
            if($i!=0){
                $movie="【".$i."】";
                $this->assign('mo',$movie);
            }

            foreach ($join as $joi){
                foreach ($joi as $jo){
                    if (date('Y-m-d')==substr($jo,0,10)){
                        $s++;
                    }
                }
            }
            if($s!=0){
                $join="【".$s."】";
                $this->assign('jo',$join);
            }

            $this->display();
        }
    }
    /*后台主页*/
    public function main(){
        if ($_SESSION['adminId']==null && $_COOKIE['adminId']==null && !isset($_SESSION['adminId'])){
            header('Content-type:text/html;charset=utf-8');
            $this->error('请先登录！！',U('Index/login'),5);
        }elseif (M('admin')->where('id='.$_SESSION['adminId'])->count()==0){
            header('Content-type:text/html;charset=utf-8');
            $this->error('信息异常!请重新登录',U('Index/login'),5);
        }else{
            $file =APP_PATH."write.txt";
            $t_time=date('ymd');
            $content = file_get_contents($file);
            if ($t_time-$content>=10000){
                $tishi="<br/>您大约有一年没清理缓存了！请立即清理<br/><br/>";
            }elseif ($t_time-$content>=300){
                $tishi="<br/>您大约有三个月没清理缓存了！请赶快清理<br/><br/>";
            }elseif($t_time-$content>=100){
                $tishi="<br/>您大约有一个月没清理缓存了！请及时清理<br/><br/>";
            }

            $movie=M('movie')->field('time')->select();
            $i=0;
            foreach ($movie as $mov){
                foreach ($mov as $mo){
                    if (date('Y-m-d')==substr($mo,0,10)){
                        $i++;
                    }
                }
            }
            if($i!=0){
                $movie="<br/>今天收到了".$i."条电影反馈！请及时查看<br/><br/>";
                $this->assign('mo',$movie);
            }


            $join=M('join')->field('time')->select();
            $s=0;
            foreach ($join as $joi){
                foreach ($joi as $jo){
                    if (date('Y-m-d')==substr($jo,0,10)){
                        $s++;
                    }
                }
            }
            if($s!=0){
                $join="<br/>今天收到了".$s."条报名信息！请及时查看<br/><br/>";
                $this->assign('jo',$join);
            }
            $this->assign('ti',$tishi);
            $this->display();
        }
    }
    /*处理登录信息*/
    public function doLogin(){
        if(!IS_POST) E('页面错误');
        $db = M('Admin');
        // 根据提交的用户名 查询数据库
        $user = $db -> where(array('username' => I('post.userName'))) -> find();
        if(!$user || $user['password'] != I('post.psw','','md5')){
            $this -> error('账号或密码错误');
        }
        // 更新监控表
        cookie('adminId',$user['id'],3600*5);
        cookie('adminName',$user['username'],3600*5);
        session('adminName',$user['username']);
        session('adminId',$user['id']);
        session('adminTh',$user['authority']);
        // 跳转到后台列表页
        $this->success('登陆成功,跳转到后台',U('Admin/Index/index'),5);
    }
    /*登录页面*/
    public function login(){
        $this->display();
    }
    public function logout(){
        $_SESSION=array();
        if(isset($_COOKIE[session_name()])){
            cookie(session_name(),"",0);
        }
        if(isset($_COOKIE['adminId'])){
            cookie("adminId","",0);
        }
        if(isset($_COOKIE['adminName'])){
            cookie("adminName","",0);
        }
        session('[destroy]');
        $this->success('退出成功',U('Index/login'),5);
    }
}