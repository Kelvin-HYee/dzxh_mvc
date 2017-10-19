<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/6
 * Time: 12:34
 */

namespace Admin\Controller;
use Think\Controller;
class SystemController extends CommonController{
    /*常规设置页面*/
    public function XhSet(){
        if ($_SESSION['adminTh']==1){
            $list=M('Admin_mes')->where('id=1')->limit(1)->select();
            $this->assign('list',$list);
            $this->display();
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function Sys_set(){
        if(!IS_POST) halt('页面错误');
        $data=array(
            'title' =>I('post.title'),
            'link' =>I('post.link'),
            'icp' =>I('post.icp'),
        );
        if(M('admin_mes')->where("id='1'")->save($data)){
                $this->success("更改成功",U('System/XhSet'));
        }else{
            $this->error('更改失败');
        }
    }
    /*数据备份还原页面*/
    public function XhBackup(){
    if ($_SESSION['adminTh']==1){
    $DataDir = "Public/databak/";
    mkdir($DataDir);
    if (!empty($_GET['Action'])) {
        import("Common.Org.MySQLReback");
        $config = array(
            'host' => C('DB_HOST'),
            'port' => C('DB_PORT'),
            'userName' => C('DB_USER'),
            'userPassword' => C('DB_PWD'),
            'dbprefix' => C('DB_PREFIX'),
            'charset' => 'UTF8',
            'path' => $DataDir,
            'isCompress' => 0, //是否开启gzip压缩
            'isDownload' => 0
        );
        $mr = new MySQLReback($config);
        $mr->setDBName(C('DB_NAME'));
            if ($_GET['Action'] == 'backup') {
            $mr->backup();
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>9,
                    'handle_o'=>'数据备份'
                );
                if(M('monitor') -> add($data)){
                      echo "<script>document.location.href='" . U("System/XhBackup") . "'</script>";
                }
//                $this->success( '数据库备份成功！');
           } elseif ($_GET['Action'] == 'RL') {
            $mr->recover($_GET['File']);
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>9,
                    'handle_o'=>'数据还原'
                );
                if(M('monitor') -> add($data)){
            echo "<script>document.location.href='" . U("System/XhBackup") . "'</script>";
                }
//                $this->success( '数据库还原成功！');
            } elseif ($_GET['Action'] == 'Del') {
            if (@unlink($DataDir . $_GET['File'])) {
                // $this->success('删除成功！');
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>9,
                    'handle_o'=>'数据删除'
                );
                if(M('monitor') -> add($data)) {
                    echo "<script>document.location.href='" . U("System/XhBackup") . "'</script>";
                }
               } else {
                $this->error('删除失败！');
              }
        }
        if ($_GET['Action'] == 'download') {
            function DownloadFile($fileName) {
                ob_end_clean();
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Length: ' . filesize($fileName));
                header('Content-Disposition: attachment; filename=' . basename($fileName));
                readfile($fileName);
            }
            DownloadFile($DataDir . $_GET['file']);
            exit();
        }
    }
    $lists = $this->MyScandir('Public/databak/');
    $this->assign("datadir",$DataDir);
    $this->assign("lists", $lists);
    $this->display();
    }else{
        $this->error("你没权限",U('Index/main'));
    }
   }

    private function MyScandir($FilePath = './', $Order = 0) {
        $FilePath = opendir($FilePath);
        while (false !== ($filename = readdir($FilePath))) {
            $FileAndFolderAyy[] = $filename;
        }
        $Order == 0 ? sort($FileAndFolderAyy) : rsort($FileAndFolderAyy);
        return $FileAndFolderAyy;
    }

    /*清理缓存页面*/
    public function XhRuntime(){
        $file =APP_PATH."write.txt";
        $t_time=date('ymd');
        $content = file_get_contents($file);
        if ($t_time-$content>=10000){
            $tishi="您大约有一年没清理了！请立即清理";
        }elseif ($t_time-$content>=300){
            $tishi="您大约有三个月没清理了！请赶快清理";
        }elseif($t_time-$content>=100){
            $tishi="您大约有一个月没清理了！请及时清理";
        }
        $this->assign('ti',$tishi);
        $this->display();
    }
    private function _deleteDir($R){
        $handle = opendir($R);
        while(($item = readdir($handle)) !== false){
            if($item != '.' and $item != '..'){
                if(is_dir($R.'/'.$item)){
                    $this->_deleteDir($R.'/'.$item);
                }else{
                    if(!unlink($R.'/'.$item))
                        die('error!');
                }
            }
        }
        closedir( $handle );
        return rmdir($R);
    }
    public function clearRuntime(){
        $R =RUNTIME_PATH;
        if($this->_deleteDir($R)){
            $myfile = fopen(APP_PATH."write.txt", "w") or die("Unable to open file!");
            $txt = date('ymd');
            fwrite($myfile, $txt);
            fclose($myfile);
        die("cleared!清理完成！！！");
        }
    }
    
    public function XhMonitor($handle=null){
        $order='id desc';
        if($handle==null){
        $Monitor = M('Monitor');
        $count = $Monitor->count();// 查询满足要求的总记录数
        $Pagecount = 10;
        $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage = 5;
        $Page->setConfig('first','首页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $Monitor->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            $Monitor = M('Monitor')->where('handle='.$handle);
            $count = $Monitor->count();// 查询满足要求的总记录数
            $Pagecount = 10;
            $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->rollPage = 5;
            $Page->setConfig('first','首页');
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('last','尾页');
            $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
            $show  = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
           $list = M('Monitor')->where('handle='.$handle)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        for($i=0;$i<=sizeof($list);$i++){
            if($list[$i]['handle']==1){
                $list[$i]['handle']='登录';
            }elseif($list[$i]['handle']==2){
                $list[$i]['handle']='文章发布';
            }elseif($list[$i]['handle']==3){
                $list[$i]['handle']='文章修改';
            }elseif($list[$i]['handle']==4){
                $list[$i]['handle']='文章删除';
            }elseif($list[$i]['handle']==5){
                $list[$i]['handle']='页面操作';
            }elseif($list[$i]['handle']==6){
                $list[$i]['handle']='更改用户信息';
            }elseif($list[$i]['handle']==7){
                $list[$i]['handle']='删除用户';
            }elseif($list[$i]['handle']==8){
                $list[$i]['handle']='添加用户';
            }elseif($list[$i]['handle']==9){
                $list[$i]['handle']='数据还原与备份及删除';
            }elseif($list[$i]['handle']==10){
                $list[$i]['handle']='修改或添加分类';
            }
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function delMonitor(){
        $count=M('Monitor')->count();
        if($count>=350){
           if ($_SESSION['adminTh']==1){
               if (M()->execute($sql = 'TRUNCATE table `xh_monitor`')){
                   $this->success('成功清除！！',U('System/XhMonitor'));
               }else{
                   $this->success('返回查看！！',U('System/XhMonitor'));
               }
           }else{
               $this->error("你没权限",U('Index/main'));
           }
        }else{
            $this->error("数据少于三百五十条不能清空！",U('System/XhMonitor'));
        }
    }
}