<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/21
 * Time: 22:09
 */

namespace Home\Controller;


class FeedController{
    /**
     * 生成RSS
     */
    public function index(){
        $link=M('admin_mes')->where('id=1')->field('link')->select();
        $name = '电子应用技术协会';
        $url=$link[0]['link'];
        $desc = '电子应用技术协会隶属于现代教育技术中心的技术类协会,作为交院为属不多的技术型协会,电协本着以人为本,为全校师生服务的原则,给全校师生带来了诸多的便捷.';
        $RSS = new \Org\Util\Rss($name, $url, $desc, ''); //依次为：网站名称，URL，描述，缩略图片
        $db = M('Article');
        $result = $db->join('__CATE__ ON __CATE__.pid=__ARTICLE__.cId')->order('id desc')->limit(20)->select();
        //pre($result);die;
        foreach($result as $list){
            $RSS->AddItem('标题：'.$list['title'].' '.'作者：'.$list['author'].' '.'分类:'.$list['name'], $url.U('/xh/'.$list['id']), '内容：'.$list['content'],'发布日期：'.$list['dateline'].' '.'点击数'.$list['clicktimes'].' '.'外链地址:'.$list['link'], date('Y-m-d H:i:s','生成RSS时间')); //查询的东西格式化，参考类文件
        }
        $RSS->Display(); //输出
    }
}