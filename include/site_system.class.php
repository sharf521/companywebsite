<?php
require_once ROOT.'/include/tb.class.php';
class sitesystemClass extends tb
{	
	public function __construct()
    {  
		$this->table='site_system';
		$this->fields=array('user_id','name','address','tel','fax','icp','tongji','logo','yuming','rexian');
		parent::__construct();
    }
	function getlist()
	{
		global $_G;
		$_system = $this->get_all();
		foreach ($_system as $key => $value){
			$system[$value['user_id']] = $value['address'];
			$system_name[$value['user_id']] = $value['name'];
			$systemid[$value['user_id']] = $value['user_id'];
		}
		$_G['system']=$system;
		$_G['system_name']=$system_name;
		$_G['systemid']=$systemid;
	}
	function add($post)
	{
		$post=$this->set($post);
		$post['addtime']=date('Y-m-d H:i:s');
		$post['status']=1;
		$post['lastip']='';
		$post['times']=0;	
		$insertid=$this->insert($post);			
	}
	function edit($post,$id)
	{
		$post=$this->set($post);
		$this->update($post,"id=$id",1);
	}
}