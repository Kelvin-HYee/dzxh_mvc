<?php

namespace Admin\Controller;
use Think\Controller;
class ArticleController extends CommonController{
    public function ArticleGuanli($order='id desc',$keywords=null){
        if ($_SESSION['adminTh']==1){
        //所有文章！！！！！！！！
        if($keywords==null){
            //在没有搜索时的分页与查询
            $Article = M('Article'); // 实例化Article对象
            $count = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->count();// 查询满足要求的总记录数
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
            $list = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            //进行了搜索后的分页与查询
            $Article = M('Article'); // 实例化Article对象
            $keywords=addslashes($keywords);
            $where=$keywords?" content like '%{$keywords}%' or title like '%{$keywords}%'":null;
            $count=$Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->count();
            $Pagecount = 10;
            $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->rollPage = 10;
            $Page->setConfig('first','首页');
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('last','尾页');
            $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
            $show  = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function ArticleFaBu(){
        $list=D('Cate')->readCate();
        $show=' <th><i class="require-red">*</i>图片：</th>
                    <td>
                        <input type="file" name="photo"/>
                    </td>';
        $this->assign('show',$show);
        $this->assign('list',$list);
        $this->display();
    }
    public function Article_f_c(){
        //文章发布处理程序
        if(!IS_POST) halt('页面错误！');
        $article_photo=null;
        if(I('post.cId')!=1){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->autoSub   =  false;
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->saveName = 'xh_'.date('ymdHis');
        //$upload->savePath  ='./Uploads/'; // 设置附件上传目录    // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['photo']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $article_photo='Uploads/'.$info['savename'];
        }
        }

        $data=array(
            'author'=>$_SESSION['adminName'],
            'title'=>I('post.title'),
            'content'=>I('post.content'),
            'photo' =>$article_photo,
            'link'=>I('post.link'),
            'dateline'=>date('y-m-d H:i:s'),
            'cId'=>I('post.cId'),
        );

        if(D('Article')->addArticle($data)){
            // 更新监控表
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>2,
                'handle_o'=>'标题为：'.I('post.title')
            );
            if(M('monitor') -> add($data)){
                $this->success("添加成功",U('Article/ArticleChaXun'));
            }
        }else{
           $this->error("添加失败");
        }
    }
    public  function ArticleChaXun($order='id desc',$keywords=null){
        //所有文章！！！！！！！！
        if($keywords==null){
            //在没有搜索时的分页与查询
        $Article = M('Article'); // 实例化Article对象
        $count = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->count();// 查询满足要求的总记录数
        $Pagecount = 10;
        $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->rollPage = 10;
            $Page->setConfig('first','首页');
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('last','尾页');
            //尾页不起作用
            //$this->lastSuffix && $this->config['last'] = $this->totalPages;
            //在源码里面（Page.class.php）将上面那句话注释掉就可以了
            $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        }else{
            //进行了搜索后的分页与查询
            $Article = M('Article'); // 实例化Article对象
            $keywords=addslashes($keywords);
            $where=$keywords?" content like '%{$keywords}%' or title like '%{$keywords}%'":null;
            $count=$Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->count();
            $Pagecount = 10;
            $Page  = new \Think\Page($count,$Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->rollPage = 10;
            $Page->setConfig('first','首页');
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('last','尾页');
            $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$Pagecount.' 条/页 共 %TOTAL_ROW% 条)');
            $show  = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $Article->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function watch($id){
        //查看文章详情
        $list=D('Article')->readArticle_One($id);
        $data['clicktimes'] = $list[0]['clicktimes']+1;
        $Click=M('Article')->where('id='.$id)->save($data);
        $this->assign('list',$list);
        $this->display();
    }
    public function ArticleFenLei(){
        if ($_SESSION['adminTh']==1) {
            $list=D('Cate')->readCate();
            $this->assign('list',$list);
            $this->display();
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function editFenLei($id){
        if(!IS_POST) halt('页面错误！');
        if ($_SESSION['adminTh']==1) {
        $data=array('name'=>I('post.cate'));
            if(D(Cate)->editCate($id,$data)){
                // 更新监控表
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>10,//修改或添加分类
                    'handle_o'=>'标题为：'.I('post.cate')
                );
                if(M('monitor') -> add($data)) {
                    $this->success("修改成功", U('Article/ArticleFenLei'));
                }
            }else{
                $this->error("修改失败");
            }
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function addFenLei(){
        if(!IS_POST) halt('页面错误！');
        if ($_SESSION['adminTh']==1) {
            $data=array('name'=>I('post.name'));
            if(D(Cate)->addCate($data)){
                // 更新监控表
                $data = array(
                    'pid' => $_SESSION['adminId'],
                    'username'=>$_SESSION['adminName'],
                    'ip' => get_client_ip(),
                    'last_time' => date('y-m-d H:i:s'),
                    'handle' =>10,//修改或添加分类
                    'handle_o'=>I('post.name')
                );
                if(M('monitor') -> add($data)) {
                    $this->success("添加成功", U('Article/ArticleFenLei'));
                }
            }else{
                $this->error("添加失败");
            }
        }else{
            $this->error("你没权限",U('Index/main'));
        }
    }
    public function editArticle($id){
        //编辑文章页面
        $list=D('Cate')->readCate();
        $reA=D('Article')->readArticle_One($id);
        $this->assign('reA',$reA);
        $this->assign('list',$list);
        $this->display();
    }
    public function Article_b_c($id){
        //为了更新监控表 所以使用文章修改处理程序
        if(!IS_POST) halt('页面错误！');
        $photo=M('Article')->field('photo')->where('id='.$id)->select();
        $data=array(
            'author'=>$_SESSION['adminName'],
            'title'=>I('post.title'),
            'content'=>I('post.content'),
            'dateline'=>date('y-m-d H:i:s'),
            'photo'=>$photo[0]['photo'],
            'cId'=>I('post.cId')
        );
        if(D('Article')->editArticle($id,$data)){
            // 更新监控表
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>3,
                'handle_o'=>'标题为：'.I('post.title')
            );
            if(M('monitor') -> add($data)){
                $this->success("修改成功",U('Article/ArticleChaXun'));
            }
        }else{
            $this->error("修改失败");
        }
    }
    /*紧急删除*/
    public function del110($id){
        if ($_SESSION['adminTh']==1) {
            $list=D('Article')->delArticle($id);
            if ($list==1){
                $this->success("文章删除成功！",U('Article/ArticleChaXun'));
            }
    }else{
            header('Content-type:text/html;charset=utf-8');
            $this->error("你没权限",U('Index/index'));
        }
    }
    public function delArticle($id){
        //文章删除
        if ($_SESSION['adminTh']==1) {
            $cId=M('Article')->field('cId')->where('id='.$id)->select();
                    if($cId[0]['cid']!=1){
                        $file=M('Article')->field('photo')->where('id='.$id)->select();
                        $files=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.$file[0]['photo'];
                        if(file_exists($files)){
                        if (!unlink($files)){
                            $this->error("图片无法删除导致文章删除不成功");
                        }}else{
                            $user = M('Article') -> where(array('id' => $id)) -> find();
                            $list=D('Article')->delArticle($id);
                            if ($list==1){
                                // 更新监控表
                                $data = array(
                                    'pid' => $_SESSION['adminId'],
                                    'username'=>$_SESSION['adminName'],
                                    'ip' => get_client_ip(),
                                    'last_time' => date('y-m-d H:i:s'),
                                    'handle' =>4,
                                    'handle_o'=>'标题为：'.$user['title']//被删标题
                                );
                                if(M('monitor') -> add($data)){
                                    $this->success("文章删除成功！",U('Article/ArticleChaXun'));
                                }
                            }elseif ($list==0){
                                $this->error("文章删除失败！");
                            }
                }
             }else{
                $user = M('Article') -> where(array('id' => $id)) -> find();
                $list=D('Article')->delArticle($id);
                if ($list==1){
                    // 更新监控表
                    $data = array(
                        'pid' => $_SESSION['adminId'],
                        'username'=>$_SESSION['adminName'],
                        'ip' => get_client_ip(),
                        'last_time' => date('y-m-d H:i:s'),
                        'handle' =>4,
                        'handle_o'=>'标题为：'.$user['title']//被删标题
                    );
                    if(M('monitor') -> add($data)){
                        $this->success("文章删除成功！",U('Article/ArticleChaXun'));
                    }
                }elseif ($list==0){
                    $this->error("文章删除失败！");
                }
            }
            }else{
            header('Content-type:text/html;charset=utf-8');
            $this->error("你没权限",U('Index/index'));
        }
    }

    public  function delMore(){
        if(!IS_POST) halt('页面错误！');
        $id=I('post.box');
        $i=0;
        foreach($id as $ide){
              $cId=M('Article')->field('cId')->where('id='.$ide)->select();
              if($cId[0]['cid']!=1){
                   $file=M('Article')->field('photo')->where('id='.$ide)->select();
                   $files=$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.$file[0]['photo'];
                      if(file_exists($files)){
                          if (!unlink($files)){
                          $this->error("图片无法删除导致文章删除不成功".$files);
                      }
                  }else{
                      $result=M('Article')->where('id='.$ide)->delete();
                      $i++;
                  }
                 }else{
                        $result=M('Article')->where('id='.$ide)->delete();
                        $i++;
               }
        }
        if(($result==0) or ($result==-1))
        {
            $this->error('没有找到记录，或者删除时出错！',U('Index/main'));
        }
        else{// 更新监控表
            $data = array(
                'pid' => $_SESSION['adminId'],
                'username'=>$_SESSION['adminName'],
                'ip' => get_client_ip(),
                'last_time' => date('y-m-d H:i:s'),
                'handle' =>4,
                'handle_o'=>'批量删除文章--'.$i.'条'//被删标题
            );
            if(M('monitor') -> add($data)){
            $this->success('文章已经删除！');
            }
        }
    }
}