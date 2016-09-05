<?php
require_once ROOT.'/include/tb.class.php';
class site_advt extends tb
{	
	public function __construct()
    {  
		$this->table='site_advt';
		$this->fields=array('id','typeid','title','image','link','addtime','endtime','status','user_id','code');
		parent::__construct();
    }
	
	
	function getlist($data = array())
	{
		global $pager;
		$_select=implode(',',$this->fields);		
		$_order=isset($data['order'])?' order by '.$data['order']:'';
		$where=isset($data['where'])?" where ".$data['where']:'';	
			
		$sql = "select SELECT from `".$this->table."` {$where} ORDER LIMIT";
		//змЬѕЪ§	
		$row=$this->mysql->get_one(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		$total = $row['num'];
		
		$epage = empty($data['epage'])?10:$data['epage'];	
		$page=$data['page'];
		if(!empty($page))
		{
			$index = $epage * ($page - 1);	
		}
		else
		{
			$index=0;$page=1;
		}		
		if($index>$total){$index=0;$page=1;}
		$limit = " limit {$index}, {$epage}";
		$list = $this->mysql->get_all(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $limit), $sql));		
		global $pager;
		$pager->page=$page;
		$pager->epage=$epage;
		$pager->total=$total;
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $pager->show()           
        );
	}
	
	
}
?>