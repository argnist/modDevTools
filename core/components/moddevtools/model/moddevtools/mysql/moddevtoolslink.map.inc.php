<?php
$xpdo_meta_map['modDevToolsLink']= array (
  'package' => 'moddevtools',
  'version' => '1.1',
  'table' => 'moddevtools_link',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'parent' => 0,
    'child' => 0,
    'link_type' => '',
  ),
  'fieldMeta' => 
  array (
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '32',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'child' => 
    array (
      'dbtype' => 'int',
      'precision' => '32',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'link_type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '11',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'unique_link' => 
    array (
      'alias' => 'unique_link',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'parent' => 
        array (
          'length' => '',
          'null' => false,
        ),
        'child' => 
        array (
          'length' => '',
          'null' => false,
        ),
        'link_type' => 
        array (
          'length' => '',
          'null' => false,
        ),
      ),
    ),
  ),
);
