<?php
class input
{
	var $modelid;
	var $fields;
	var $data;

    function __construct($modelid)
    {
		$this->db = content_model_field::model();
		$this->db_pre = $this->db->db_tablepre;
		$this->modelid = $modelid;
		$this->fields = getcache('model_field_'.$modelid,'model');
		//初始化附件类
		$this->siteid = $this->_context->get_cookie('siteid');
		$this->attachment = new attachment('content','0',$this->siteid);
		$this->site_config = getcache('sitelist','admin');
		$this->site_config = $this->site_config[$this->siteid];
    }

	function get($data,$isimport = 0)
	{
		$this->data = $data = trim_script($data);
		$info = array();
		foreach($data as $field=>$value)
		{
			//if(!isset($this->fields[$field]) || check_in($_roleid, $this->fields[$field]['unsetroleids']) || check_in($_groupid, $this->fields[$field]['unsetgroupids'])) continue;
			$name = $this->fields[$field]['name'];
			$minlength = $this->fields[$field]['minlength'];
			$maxlength = $this->fields[$field]['maxlength'];
			$pattern = $this->fields[$field]['pattern'];
			$errortips = $this->fields[$field]['errortips'];
			if(empty($errortips)) $errortips = $name.' 不符合要求';
			$length = empty($value) ? 0 : (is_string($value) ? strlen($value) : count($value));

			if($minlength && $length < $minlength)
			{
				if($isimport)
				{
					return false;
				}
				else
				{
					$this->_app->showmessage('300',$name.' 不得少于 '.$minlength.'字符');
				}
			}
			if($maxlength && $length > $maxlength)
			{
				if($isimport)
				{
					$value = str_cut($value,$maxlength,'');
				}
				else
				{
					$this->_app->showmessage('300',$name.' 不得多于 '.$maxlength.'字符');
				}
			}
			elseif($maxlength)
			{
				$value = str_cut($value,$maxlength,'');
			}
			if($pattern && $length && !preg_match($pattern, $value) && !$isimport) showmessage($errortips);
			$MODEL = getcache('model', 'model');
            $this->db->table_name = $this->fields[$field]['issystem'] ? $this->db_pre.$MODEL[$this->modelid]['tablename'] : $this->db_pre.$MODEL[$this->modelid]['tablename'].'_data';
            if($this->fields[$field]['isunique'] && $this->db->get_one(array($field=>$value),$field) && ROUTE_A != 'edit') $this->_app->showmessage('300',$name.'的值不得重复！');
			$func = $this->fields[$field]['formtype'];
			if(method_exists($this, $func)) $value = $this->$func($field, $value);
			if($this->fields[$field]['issystem'])
			{
				$info['system'][$field] = $value;
			}
			else
			{
				$info['model'][$field] = $value;
			}
			//颜色选择为隐藏域 在这里进行取值
			$info['system']['style'] = $_POST['style_color'] ? strip_tags($_POST['style_color']) : '';
			if($_POST['style_font_weight']) $info['system']['style'] = $info['system']['style'].';'.strip_tags($_POST['style_font_weight']);
		}
		return $info;
	}

 } 
?>