<?php
/******************************
 * $File: module.class.php
 * $Description: ģ���ദ���ļ�
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/

class moduleClass {
	const ERROR = '���������������Ա��ϵ';
	const MODULE_NAME_NO_EMPTY = 'ģ�����Ʋ���Ϊ��';
	const MODULE_CODE_NO_EMPTY = 'ģ���ʶ������Ϊ��';
	const MODULE_INSTALL_YES = '��ģ���Ѿ���װ';
	const MODULE_PURVIEW_NO_EMPTY = 'ģ���Ȩ�ޱ�����д';
	const FIELDS_NAME_NO_ALLOW = '�ֶν�ֹ�ı�ʶ�����ֶ��Ѿ�����';
	const FIELDS_TYPE_NO_SYSTEM = 'ϵͳģ���ֹ���ֶ�';
	const FIELDS_UPDATE_ERROR = '�ֶθ��´����������Ա��ϵ';
	//���ģ����б�
	public static function  getlist($data = array()){
		global $mysql;
		
		//�Ѱ�װ��ģ��
		$sql = "select * from  {module}  order by `showorder` desc ";
		$module_list = $mysql->db_fetch_arrays($sql);
		
		
		

		return $result;
	
	}
	
	//���ģ����б�
	public static function  getone($data = array()){
		global $mysql;
		
		$code = isset($data['code'])?$data['code']:"";
		if (empty($code)) return array();
		
		//�Ѱ�װ��ģ��
		$sql = "select * from  {module}  where code = '{$code}' limit 1";
		return  $mysql->get_one($sql);
	}
	
	
}
?>