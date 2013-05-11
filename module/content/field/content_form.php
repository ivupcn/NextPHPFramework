<?php
class content_form
{
	var $modelid;
	var $fields;
	var $id;
	var $formValidator;

    function __construct($modelid,$catid = 0,$categorys = array())
    {
		$this->modelid = $modelid;
		$this->catid = $catid;
		$this->categorys = $categorys;
		$this->fields = getcache('model_field_'.$modelid,'model');
		$this->siteid = SITEID;
    }

	function get($data = array())
	{
		$_groupid = $_SESSION['groupid'];
		$this->data = $data;
		if(isset($data['id'])) $this->id = $data['id'];
		$info = array();
		foreach($this->fields as $field=>$v)
		{
			if(defined('IN_ADMIN'))
			{
				if($v['iscore'] || check_in($_SESSION['roleid'], $v['unsetroleids'])) continue;
			}
			else
			{
				if($v['iscore'] || !$v['isadd'] || check_in($_groupid, $v['unsetgroupids'])) continue;
			}
			$func = $v['formtype'];
			$value = isset($data[$field]) ? htmlspecialchars($data[$field], ENT_QUOTES) : '';
			if($func=='pages' && isset($data['maxcharperpage']))
			{
				$value = $data['paginationtype'].'|'.$data['maxcharperpage'];
			}
			if(!method_exists($this, $func)) continue;
			$form = $this->$func($field, $value, $v);
			if($form !== false)
			{
				$star = $v['minlength'] || $v['pattern'] ? 1 : 0;
				$info[$field] = array('name'=>$v['name'], 'tips'=>$v['tips'], 'form'=>$form, 'star'=>$star,'formtype'=>$v['formtype']);
			}
		}
		return $info;
	}
}?>