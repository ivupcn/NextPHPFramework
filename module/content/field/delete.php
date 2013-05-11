<?php
defined('IN_ADMIN') or exit('No permission resources.');

content_model_field::model()->exec("ALTER TABLE `$tablename` DROP `$field`");
?>