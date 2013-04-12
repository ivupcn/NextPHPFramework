<?php
return array (
  'user' => 
  array (
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
    'index' => 
    array (
      'login' => 
      array (
        'role' => 'ACL_EVERYONE',
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
      'setSiteid' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'sessionLife' => 
      array (
        'role' => 'ACL_HAS_ROLE',
      ),
      'main' => 
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
  'test' => 
  array (
    'index' => 
    array (
      'init' => 
      array (
        'role' => 'ACL_EVERYONE',
      ),
    ),
  ),
);
?>