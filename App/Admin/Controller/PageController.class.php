<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/6
 * Time: 12:33
 */

namespace Admin\Controller;
Use Think\Controller;
class PageController extends CommonController{
    /*电子海报页面*/
    public function xczp(){
        $this->display();
    }

    /*联系qq页面*/
    public function qq(){
        $list=M('qq')->where("id='1'")->select();
        $this->assign('list',$list);
        $this->display();
    }


    /*我们团队页面*/
    public function gywm(){
        if ($_SESSION['adminTh']==1) {
            $list = M('member')->where("id='1'")->select();
            $this->assign('list', $list);
            $this->display();
        }else{
                $this->error("你没权限",U('Index/main'));
            }
    }

    /*招新报名页面*/
    public function zxbm(){
        $count =M('join')->count();
        $Pagecount = 10;
        $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage = 10;
        $Page->setConfig('first','首页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show  = $Page->show();
        $list = M('join')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /*服务列表页面*/
    public function yxzl(){
        $list=M('admin_mes')->field('id,cate')->where('id!=1')->select();
        $this->assign('list',$list);
        $this->display();
    }

    /*电影反馈页面*/
    public function xxfk(){
        $count =M('movie')->count();
        $Pagecount = 10;
        $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage = 10;
        $Page->setConfig('first','首页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show  = $Page->show();
        $list = M('movie')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }


    /*----------------各页面处理项------------------*/

    /*电影反馈处理*/
    public function xxfk_c($id){
        if ($_SESSION['adminTh']==1) {
            if(M('movie')->where('id='.$id)->delete()){
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>5,
                    'handle_o'=>'删除了id为'.$id.'的电影反馈'
                );
                if(M('monitor') -> add($data)){
                    $this->success('删除成功');
                }
            }else{
                $this->error('删除失败');
            }
        }else{
            header('Content-type:text/html;charset=utf-8');
            $this->error("你没权限",U('Index/index'));
        }
    }
    /*报名处理函数*/
    public function zxbm_c($id){
        if ($_SESSION['adminTh']==1) {
            if(M('join')->where('id='.$id)->delete()){
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>5,
                    'handle_o'=>'删除了id为'.$id.'的报名通知'
                );
                if(M('monitor') -> add($data)){
                    $this->success('删除成功');
                }
            }else{
                $this->error('删除失败');
            }
        }else{
            header('Content-type:text/html;charset=utf-8');
            $this->error("你没权限",U('Index/index'));
        }
    }

    public function xczp_c(){
        if(!IS_POST) halt('页面错误');
        $file="./Uploads/xuanc";
        if (file_exists($file))
        {
            $this->delDirAndFile($file);
        }elseif(!file_exists($file)){
            mkdir ($file, 0777, true );
        }
        $config = array(
            'maxSize'    =>    3145728,
            'rootPath'   =>    './Uploads/',//文件上传保存的根路径
            'savePath'   =>    'xuanc/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    false,
            'saveName' => array('uniqid','')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info = $upload->upload();
        if(!$info){
            $this->error($upload->getError());
        }
        $data=array(
            'path_photo1' =>'Uploads/xuanc/'.$info[0]['savename'],
            'path_photo2' =>'Uploads/xuanc/'.$info[1]['savename'],
            'path_photo3' =>'Uploads/xuanc/'.$info[2]['savename'],
            'path_photo4' =>'Uploads/xuanc/'.$info[3]['savename']
        );
        if(M('dzhb')->where("id='1'")->save($data)){
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>5,
                'handle_o'=>'更改了宣传照片'
            );
            if(M('monitor') -> add($data)){
                $this->success("更改成功",U('Page/xczp'));
            }
        }else{
            $this->error('更改失败');
        }
    }
    public function AddQq(){
        if(!IS_POST) halt('页面错误');
        $data=array(
            'qq1' =>I('post.qq1'),
            'qq2' =>I('post.qq2'),
            'qq3' =>I('post.qq3'),
            'qq4' =>I('post.qq4'),
            'qq5' =>I('post.qq5'),
            'name1' =>I('post.name1'),
            'name2' =>I('post.name2'),
            'name3' =>I('post.name3'),
            'name4' =>I('post.name4'),
            'name5' =>I('post.name5')
        );
        if(M('qq')->where("id='1'")->save($data)){
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>5,
                'handle_o'=>'更改了联系qq页面'
            );
            if(M('monitor') -> add($data)){
                $this->success("更改成功",U('Index/main'));
            }
        }else{
            $this->error('更改失败');
        }
    }
    public function gywm_c(){
        if(!IS_POST) halt('页面错误');
        $file="./Uploads/member";
        if (file_exists($file))
        {
            $this->delDirAndFile($file);
        }
        if(!file_exists($file)){
            mkdir ($file, 0777, true );
        }
        $config = array(
            'maxSize'    =>    3145728,
            'rootPath'   =>    './Uploads/',//文件上传保存的根路径
            'savePath'   =>    'member/',
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    false,
            'saveName' => array('uniqid','')
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info = $upload->upload();
        if(!$info){
            $this->error($upload->getError());
        }
        $data=array(
            'name1' =>I('post.name1'),
            'name2' =>I('post.name2'),
            'name3' =>I('post.name3'),
            'name4' =>I('post.name4'),
            'name5' =>I('post.name5'),
            'name6' =>I('post.name6'),
            'f_name1' =>I('post.f_name1'),
            'f_name2' =>I('post.f_name2'),
            'f_name3' =>I('post.f_name3'),
            'f_name4' =>I('post.f_name4'),
            'f_name5' =>I('post.f_name5'),
            'f_name6' =>I('post.f_name6'),
            'path_photo1' =>'Uploads/member/'.$info[0]['savename'],
            'path_photo2' =>'Uploads/member/'.$info[1]['savename'],
            'path_photo3' =>'Uploads/member/'.$info[2]['savename'],
            'path_photo4' =>'Uploads/member/'.$info[3]['savename'],
            'path_photo5' =>'Uploads/member/'.$info[4]['savename'],
            'path_photo6' =>'Uploads/member/'.$info[5]['savename']
        );
        if(M('member')->where("id='1'")->save($data)){
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>5,
                'handle_o'=>'更改了我们团队页面'
            );
            if(M('monitor') -> add($data)){
                $this->success("更改成功",U('Index/main'));
            }
        }else{
            $this->error('更改失败');
        }
    }

    public function yxzl_c(){
        if(!IS_POST) halt('页面错误');
        $data=array(
            'cate'=>I('post.cate'),
            'title'=>1,
            'link'=>1
        );
        if(M('admin_mes')->add($data)){
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>5,
                'handle_o'=>'添加了服务列表页面'
            );
            if(M('monitor') -> add($data)){
                $this->success("添加成功",U('Index/main'),5);
            }
        }else{
            $this->error('添加失败！');
        }
    }

    public function yxzl_d($id){
        if ($_SESSION['adminTh']==1) {
            if($id!=1){
                if(M('admin_mes')->where('id='.$id)->delete()){
                    $data = array(
                        'pid' => $_SESSION['adminId'],
                        'username'=>$_SESSION['adminName'],
                        'ip' => get_client_ip(),
                        'last_time' => date('y-m-d H:i:s'),
                        'handle' =>5,
                        'handle_o'=>'删除了服务列表页面'
                    );
                    if(M('monitor') -> add($data)){
                        $this->success('删除成功',$this->site_url,10);
                    }
                }else{
                    $this->error('删除失败');
                }
            }else{
                $this->error('id为1的不能删哦');
            }
        }else{
            header('Content-type:text/html;charset=utf-8');
            $this->error("你没权限",U('Index/index'));
        }
    }

    function delDirAndFile($path, $delDir = FALSE) {
        if (is_array($path)) {
            foreach ($path as $subPath)
                delDirAndFile($subPath, $delDir);
        }
        if (is_dir($path)) {
            $handle = opendir($path);
            if ($handle) {
                while (false !== ( $item = readdir($handle) )) {
                    if ($item != "." && $item != "..")
                        is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
                }
                closedir($handle);
                if ($delDir)
                    return rmdir($path);
            }
        } else {
            if (file_exists($path)) {
                return unlink($path);
            } else {
                return FALSE;
            }
        }
        clearstatcache();
    }
}