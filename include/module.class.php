<?php
/******************************
 * $File: module.class.php
 * $Description: 模块类处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/

class moduleClass {
	const ERROR = '操作有误，请跟管理员联系';
	const MODULE_NAME_NO_EMPTY = '模块名称不能为空';
	const MODULE_CODE_NO_EMPTY = '模块标识名不能为空';
	const MODULE_INSTALL_YES = '此模块已经安装';
	const MODULE_PURVIEW_NO_EMPTY = '模块的权限必须填写';
	const FIELDS_NAME_NO_ALLOW = '字段禁止的标识名或字段已经存在';
	const FIELDS_TYPE_NO_SYSTEM = '系统模块禁止加字段';
	const FIELDS_UPDATE_ERROR = '字段更新错误，请跟管理员联系';
	//获得模块的列表
	public static function  getlist($data = array()){
		global $mysql;
		
		//已安装的模块
		$sql = "select * from  {module}  order by `showorder` desc ";
		$module_list = $mysql->db_fetch_arrays($sql);
		
		
		

		return $result;
	
	}
	
	//获得模块的列表
	public static function  getone($data = array()){
		global $mysql;
		
		$code = isset($data['code'])?$data['code']:"";
		if (empty($code)) return array();
		
		//已安装的模块
		$sql = "select * from  {module}  where code = '{$code}' limit 1";
		return  $mysql->get_one($sql);
	}
	
	
}
?>