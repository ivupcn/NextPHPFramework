-- 从表
CREATE TABLE `$table_data` (
  `id` mediumint(8) unsigned default '0',
  `content` text NOT NULL,
  `comment` tinyint(1) unsigned NOT NULL default '1',
  `relation` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `unsetgroupids`, `unsetroleids`, `iscore`, `isunique`, `isbase`, `issearch`, `isadd`, `isfulltext`, `isposition`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'content', '内容', '', '', 1, 999999, '', '内容不能为空', 'editor', 'a:4:{s:7:"toolbar";s:5:"basic";s:12:"defaultvalue";s:0:"";s:15:"enablesaveimage";s:1:"0";s:6:"height";s:3:"200";}', '', '', '', 0, 0, 1, 0, 1, 1, 0, 51, 0);
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `unsetgroupids`, `unsetroleids`, `iscore`, `isunique`, `isbase`, `issearch`, `isadd`, `isfulltext`, `isposition`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'comment', '评论', '', '', 0, 0, '', '', 'box', 'a:8:{s:7:"options";s:31:"允许评论|1不允许评论|0";s:7:"boxtype";s:5:"radio";s:9:"fieldtype";s:7:"tinyint";s:9:"minnumber";s:1:"1";s:5:"width";s:2:"88";s:4:"size";s:1:"1";s:12:"defaultvalue";s:1:"1";s:10:"outputtype";s:1:"0";}', '', '', '', 0, 0, 0, 0, 0, 0, 0, 81, 0);