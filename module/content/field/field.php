<?php
$fields = array('text'=>'单行文本',
				'textarea'=>'多行文本',
				'editor'=>'编辑器',
				'catid'=>'栏目',
				'title'=>'标题',
				'box'=>'选项',
				'image'=>'图片',
				'images'=>'多图片',
				'number'=>'数字',
				'datetime'=>'日期和时间',
				'posid'=>'推荐位',
				'keyword'=>'关键词',
				'author'=>'作者',
				'copyfrom'=>'来源',
				'groupid'=>'会员组',
				'islink'=>'转向链接',
				'view'=>'模板',
				'pages'=>'分页选择',
				'typeid'=>'类别',
				'readpoint'=>'积分、点数',
				'linkage'=>'联动菜单',
				'downfile'=>'镜像下载',
				'downfiles'=>'多文件上传',
				'map'=>'地图字段',
				'video'=>'视频库'
				);
//不允许删除的字段，这些字段将不会在字段添加处显示
$not_allow_fields = array('catid','title','thumb','updatetime','inputtime','url','listorder','status','username','keywords','posids','description');
//允许添加但必须唯一的字段
$unique_fields = array('pages','readpoint','author','copyfrom','islink');
//禁止被禁用的字段列表
$forbid_fields = array('catid','title','updatetime','inputtime','url','listorder','status','view','username','thumb','keywords','posids','description');
//禁止被删除的字段列表
$forbid_delete = array('catid','title','thumb','updatetime','inputtime','url','listorder','status','view','username','keywords','posids','description');
//禁止被修改的字段列表
$forbid_edit = array('catid','title','thumb','updatetime','inputtime','url','listorder','status','view','username','keywords','posids','description');
//可以追加 JS和CSS 的字段
$att_css_js = array('text','textarea','box','number','keyword','typeid');
?>