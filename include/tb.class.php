<?php
defined('ROOT') or exit('Access Denied');
class tb
{
	var $fields=array();
	var $table='';
	var $mysql;
    public function __construct()
    {	
		global $mysql,$db_config;
        $this->mysql=$mysql;
		$this->table=$db_config['prefix'].$this->table;
    }

    function get_one($data=array())
    {
		$limit=isset($data['limit'])?" limit ".$data['limit']:'limit 0,1';
		$where=isset($data['where'])?" where ".$data['where']:'';
		$order=isset($data['order'])?' order by '.$data['order']:'';		
		$field=implode(',',$this->fields);
		
		$sql="select $field from $this->table $where $order $limit";
		return $this->mysql->get_one($sql,'no');
    }
	
	function get_all($data=array())
	{			
		$limit=isset($data['limit'])?" limit ".$data['limit']:'limit 0,5000';
		$where=isset($data['where'])?" where ".$data['where']:'';
		$order=isset($data['order'])?' order by '.$data['order']:'';		
		$field=implode(',',$this->fields);
		$sql="select $field from $this->table $where $order $limit";
		return $this->mysql->get_all($sql,'no');
	}	
	
	function insert($post)
	{
		$sqlk = $sqlv = '';
		foreach($post as $k=>$v) 
		{
			if(in_array($k, $this->fields)) { $sqlk .= ','.$k; $sqlv .= ",'$v'"; }
		}
		$sqlk = substr($sqlk, 1);
		$sqlv = substr($sqlv, 1);
		//echo "INSERT INTO $this->table ($sqlk) VALUES ($sqlv)";		
		$this->mysql->query("INSERT INTO $this->table ($sqlk) VALUES ($sqlv)",'no');
		return $this->mysql->get_insert_id();
	}
	function update($post,$where='',$limit='')
	{
		if($limit!='') $limit='limit 1';
		foreach($post as $k=>$v) 
		{
			if(in_array($k, $this->fields)) $sql .= ",$k='$v'";
		}
        $sql = substr($sql, 1);
	    $this->mysql->query("UPDATE $this->table SET $sql WHERE $where $limit",'no');
	}
	
	function drop($where='')
	{
	    $this->mysql->query("delete from $this->table WHERE $where ",'no');
	}
	
	function getone_data()
	{
		$field='';
		foreach($this->fields as $f)
		{
			if($field=='')
				 $field='a.'.$f;
			else
				 $field.=',a.'.$f;		
		}
		if($this->id)
			$sql="select $field,b.content from $this->table a left join ".$this->table.'_data'." b on a.id=b.id where a.id=".$this->id." limit 0,1";
		
		return $this->mysql->get_one($sql);
	}
	function insert_data($post)
	{
		$inserid=$this->insert($post);
		
		return $this->mysql->query("insert into ".$this->table."_data(id,content)values($inserid,'".$post['content']."')");		
	}
	
	function update_data($post)
	{	
		$this->update($post,"id=$this->id");
		
	return $this->mysql->query("UPDATE ".$this->table."_data SET content='".$post['content']."' WHERE id=$this->id limit 1");
		
	}
	
}
?>