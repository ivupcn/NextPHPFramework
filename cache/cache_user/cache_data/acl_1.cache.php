<?php
return array (
  'user' => 
  array (
    'index' => 
    array (
      'login' => 
      array (
        'role' => 'ACL_EVERYONE',
      ),
    ),
    'acl' => 
    array (
      'setting' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
    ),
    'user' => 
    array (
      'init' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'editPwd' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'editInfo' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
    ),
    'group' => 
    array (
      'init' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
    ),
  ),
  'admin' => 
  array (
    'site' => 
    array (
      'config' => 
      array (
        'role' => '29,30',
      ),
    ),
    'index' => 
    array (
      'main' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'sessionLife' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'setSiteid' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'menuLeft' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'logout' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'login' => 
      array (
        'role' => 'ACL_NO_ROLE',
      ),
      'init' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
    ),
    'panel' => 
    array (
      'init' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
    ),
  ),
);
?>