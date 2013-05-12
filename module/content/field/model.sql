-- 从表
CREATE TABLE `$table_data` (
  `id` mediumint(8) unsigned default '0',
  `content` text NOT NULL,
  `comment` tinyint(1) unsigned NOT NULL default '1',
  `relation` varchar(255) NOT NULL default '',
  KEY `id` (`id`)
) TYPE=MyISAM;

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `unsetgroupids`, `unsetroleids`, `iscore`, `isunique`, `isbase`, `issearch`, `isadd`, `isfulltext`, `isposition`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'content', '内容', '', '', 1, 999999, '', '内容不能为空', 'editor', '{"toolbar":"basic","defaultvalue":"","enablesaveimage":"0","height":"200"}', '', '', '', 0, 0, 1, 0, 1, 1, 0, 51, 0);
INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `unsetgroupids`, `unsetroleids`, `iscore`, `isunique`, `isbase`, `issearch`, `isadd`, `isfulltext`, `isposition`, `listorder`, `disabled`) VALUES($modelid, $siteid, 'comment', '评论', '', '', 0, 0, '', '', 'box', '{"options":"\u5141\u8bb8\u8bc4\u8bba|1\\r\\n\u4e0d\u5141\u8bb8\u8bc4\u8bba|0","boxtype":"radio","fieldtype":"tinyint","minnumber":"1","width":"88","size":"1","defaultvalue":"1","outputtype":"0"}', '', '', '', 0, 0, 0, 0, 0, 0, 0, 81, 0);