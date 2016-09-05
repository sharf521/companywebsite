<?php
error_reporting(7);
header("content-Type: text/html; charset=gbk");
date_default_timezone_set('Asia/Shanghai');//时区配置

session_cache_limiter('private, must-revalidate');//返回页面不清空缓存 
session_start();


$request_uri = explode("?",$_SERVER['REQUEST_URI']);
if(isset($request_uri[1])){
	$rewrite_url = explode("&",$request_uri[1]);
	foreach ($rewrite_url as $key => $value){
		$_value = explode("=",$value);
		if (isset($_value[1])){
			$_REQUEST[$_value[0]] = addslashes($_value[1]);
		}
	}
}

/* 检查和转义字符 */
function safe_str($str){
	if(!get_magic_quotes_gpc())	{
		if( is_array($str) ) {
			foreach($str as $key => $value) {
				$str[$key] = safe_str($value);
			}
		}else{
			$str = addslashes($str);
		}
	}
	return $str;
}
foreach(array('_GET','_POST','_COOKIE','_REQUEST') as $key)
{
	if (isset($$key))
	{
		foreach($$key as $_key => $_value)
		{
			$$key[$_key] = safe_str($_value);
		}
	}	
}

define('ROOT', dirname(__FILE__));

require ROOT.'/data/config.php';

$_G=array();

require ROOT.'/include/mysql.class.php';
$mysql = new Mysql($db_config);

require ROOT.'/include/global.inc.php';


require ROOT.'/include/page.class.php';
//$page=new Page();
$pager=new Page();

require ROOT.'/include/module.class.php';

require ROOT.'/include/site_system.class.php';
$systemClass=new sitesystemClass();
$systemClass->getlist();

require ROOT.'/include/magic.class.php';
$magic = new Magic();
$magic->template_dir = './themes';
$magic->compile_dir='./data/compile';
//$magic->plugins_dir='includes/magic';

?>