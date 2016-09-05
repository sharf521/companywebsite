<?php
require './init.php';

$is_xist_site=false;
$url=$_SERVER['HTTP_HOST'];
require_once ROOT.'/modules/sitesystem/sitesystem.class.php';
$sitesys=new site_system();
$row=$sitesys->get_one(array("where"=>"yuming like '%$url%'"));
if(empty($row))
{
	$db_config['user']     = $db_config['user_gx'];
	$db_config['pwd']      = $db_config['pwd_gx'] ;
	$db_config['name']     = $db_config['name_gx'];	
	$mysql = new Mysql($db_config);	
	$row=$sitesys->get_one(array("where"=>"yuming like '%$url%'"));
	if(empty($row))
	{
		$template='404_jianshe.html';
	}
	else
	{
		if($row['status']!=1)
		{
			$template='404_shenhe.html';
		}
		else
		{
			$is_xist_site=true;	
		}
	}
}
else
{
	if($row['status']!=1)
	{
		$template='404_shenhe.html';
	}
	else
	{
		$is_xist_site=true;	
	}
}
if($is_xist_site)
{
$_G['u_userid']=$row['user_id'];
$u_userid=$_G['u_userid'];

$sql="select c.city_yuming from {member} m left join {city} c on m.city=c.city_id where m.user_id=$u_userid limit 1" ;

$city=$mysql->get_one($sql);
$city['city_yuming']=explode(',',$city['city_yuming']);


$_G['ucity']=$row;

//$_G['ucity']['logo']="/img.php?u=".DeCode($_G['ucity']['logo'],'E');//加密
$_G['img_path']="http://".$city['city_yuming'][0]."/";
define('image_path',$_G['img_path'] );

$_G['ucity']['logo']=image_path.$_G['ucity']['logo'];

$moban=$row['code'];//模版
$qq=$row['qq'];//QQ
$re=explode("|",$qq);
$_G['qq']=$re;

$magic->template_dir = './themes/'.$moban;
$magic->compile_dir='./data/compile/'.$moban;
$magic->assign("tpldir",'/themes/'.$moban);



$_G['query_string'] = explode("&",$_SERVER['QUERY_STRING']);

//user  system
//index.php?user&q=login  
$_G['query_site']=$_G['query_string'][0];

$q = empty($_REQUEST['q'])?"":$_REQUEST['q'];//获取内容

$_G['query'] = $q;
$_q = explode("/",$q);

$_G['query_sort'] = empty($_q[0])?"main":$_q[0];
$_G['query_class'] = empty($_q[1])?"list":$_q[1];
$_G['query_type'] = empty($_q[2])?"list":$_q[2];
$_G['query_url'] = "?{$_G['query_site']}&q={$_G['query_sort']}/{$_G['query_class']}";




$quer = explode("/",$_G['query_string'][0]);
if(isset($quer[1]))
{
	$_G['query_site']= $quer[0];
	$article_id = $quer[1];
}	


$querysite=$_G['query_site'];

//类型
require_once ROOT.'/modules/sitecat/sitecat.class.php';
$sitecat=new sitecat();
$_G['lei']=$sitecat->get_one(array("where"=>"nid='$querysite' "));


if ($_G['lei']['code'] == "article" )
{
	require "modules/site_news/site_news.inc.php";
	exit();
}

require_once ROOT.'/modules/site_news/site_news.class.php';
$sitenews=new site_news();
//最新公告
$news=$sitenews->get_one(array("where"=>"user_id='$u_userid' and  categoryid=8 ","order "=>"id desc ","limit"=>'1'));
$_G['news']=$news;
$_G['news']['content']=strip_tags($_G['news']['content']);



$_G['news']['content']=substr($_G['news']['content'],0,252);
//公司简介
$news1=$sitenews->get_one(array("where"=>"user_id='$u_userid' and  categoryid=1 ","order "=>"id desc ","limit"=>'1'));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
$_G['news1']=$news1;
$_G['news1']['content']=substr($_G['news1']['content'],0,1790);

//新闻中心
$news2=$sitenews->get_all(array("where"=>"user_id='$u_userid' and  categoryid=6  and status=1 ","order "=>"id desc ","limit"=>'6'));
$_G['news2']=$news2;

//商品展示
require_once ROOT.'/modules/goods/goods.class.php';
$goods=new goods();

$goods=$goods->get_all(array("where"=>"store_id='$u_userid' and  if_show=1 and closed=0 ","order "=>"goods_id desc ","limit"=>'8'));
$_G['goods']=$goods;

foreach($goods as $key=>$var)
{
	$_G['goods'][$key]['goods_name1']=substr($var['goods_name'],0,52);
	if($var['default_image'])
	{
		if(strpos(strtolower($var['default_image']),'http://') ===false)
			$_G['goods'][$key]['default_image']=image_path.$var['default_image'];
	}
	//$_G['goods'][$key]['default_image']="/img.php?u=".DeCode($var['default_image'],'E');
}

//商品分类
require_once ROOT.'/modules/gcategory/gcategory.class.php';
$gcategory=new gcategory();
$fenlei = $gcategory->get_list(0, true);

	 foreach ( $fenlei as $key => $val)
	 {
		   $children = $gcategory->get_list($val['cate_id'], true);
		   foreach ( $children as $k => $value)
		   {
			   $third_children = $gcategory->get_list($value['cate_id'], true);
			   $children[$k]['children'] = $third_children;
			   unset($third_children);
		   }
		   $fenlei[$key]['children'] = $children;
		   unset($children);
	  } 

$_G['fenlei']=$fenlei;


/*if($moban=='default')
{*/
//轮播广告
	require_once ROOT.'/modules/site_advt/site_advt.class.php';
	$site_advt=new site_advt();
	$riqi=date('Y-m-d H:i:s');
	
	$advt=$site_advt->get_all(array("where"=>"user_id='$u_userid' and  status=1 and  addtime<'$riqi' and endtime>'$riqi' and typeid=1 ","order "=>"id desc "));
	$_G['advt']=$advt;
	
	foreach($advt as $key=>$var)
	{
		$_G['advt'][$key]['bian']=$key+1;
		//$_G['advt'][$key]['image']="/img.php?u=".DeCode($var['image'],'E');
		$_G['advt'][$key]['image']=image_path.$var['image'];
		if($key==0)
		{
			$_G['link1']=$var['link'];
			//$_G['img1']="/img.php?u=".DeCode($var['image'],'E');
			$_G['img1']=image_path.$var['image'];
		}
	}

/*}*/

/*if($moban=="shopcom")
{
	require_once ROOT.'/modules/site_advt/site_advt.class.php';
	$site_advt=new site_advt();
	$riqi=date('Y-m-d H:i:s');
	$advt=$site_advt->get_one(array("where"=>"user_id='$u_userid' and  status=1 and  addtime<'$riqi' and endtime>'$riqi' and typeid=4 and code='$moban' ","order "=>"id desc "));
	$_G['advt']=$advt;
	if($advt['image'])
	$_G['advt']['image']=image_path.$advt['image'];
}*/




//友情链接

require_once ROOT.'/modules/sitelink/site_link.class.php';
$sitelink=new site_link();
$_G['link']=$sitelink->get_all(array("where"=>"store_id='$u_userid' "));
$_G['inde']=1;
foreach($_G['link'] as $key=>$var)
{
	$_G['link'][$key]['logo']=image_path.$var['logo'];
	//$_G['link'][$key]['logo']="/img.php?u=".DeCode($var['logo'],'E');
}

$template='index.html';	
/*}*/
}
$magic->assign('_G',$_G);
$magic->display($template);