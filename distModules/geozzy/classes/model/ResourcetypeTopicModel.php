<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourcetypeTopicModel extends Model {

  static $tableName = 'geozzy_resourcetype_topic';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resourceType' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourcetypeModel',
      'key' => 'id'
    ),
    'topic' => array(
      'type'=>'FOREIGN',
      'vo' => 'TopicModel',
      'key' => 'id'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
