<?php
require_once ROOT.'/include/tb.class.php';
class gcategory extends tb
{	
	public function __construct()
    {  
		$this->table='gcategory';
		$this->fields=array('cate_id','store_id','cate_name','parent_id','sort_order','if_show','city','is_xianshi');
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
		
		$pager->page=$page;
		$pager->epage=$epage;
		$pager->total=$total;
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $pager->show()           
        );
	}
	
	function get_list($parent_id = -1, $shown = false)
	{
		global $_G;
	
		$conditions = "1 = 1";
		$shown && $conditions .= " AND if_show = 1 ";
		$parent_id >= 0 && $conditions .= " AND parent_id = '$parent_id'"; 
		$conditions .=' and store_id='.$_G['u_userid'] ;
		
		$list=$this->get_all(array("where"=>"$conditions"));
		
		return $list;
	}
	
	
}
?>