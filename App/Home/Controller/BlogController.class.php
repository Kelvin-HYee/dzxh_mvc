<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 0:17
 */

namespace Home\Controller;
use Think\Controller;
class BlogController extends Controller {
    public function index(){
        $inform=M('Article')->field('id,title,author')->order('id desc')->where('cId=1')->limit(4)->select();
        $most=M('Article')->field('id,title,dateline')->order('clicktimes desc')->where('cId!=1')->limit(5)->select();

        $count = M('Article')->where('cId=5')->count();
        $Pagecount = 3;
        $Page = new \Think\Page($count, $Pagecount);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->rollPage = 3;
        $Page->setConfig('first', '首页');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('last', '尾页');
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% 第 ' . I('p', 1) . ' 页/共 %TOTAL_PAGE% 页 ( ' . $Pagecount . ' 条/页 共 %TOTAL_ROW% 条)');
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $blog = M('Article')->order('id desc')->where('cId=5')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $icpp=M('admin_mes')->field('icp')->where('id=1')->select();
        $icp=$icpp[0]['icp'];
        $this->assign('icp',$icp);
        $this->assign('blog',$blog);
        $this->assign('show',$show);
        $this->assign('most',$most);
        $this->assign('inform',$inform);
        $this->display();
    }
   
}