<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeFile extends Module {

  public $name = 'rtypeFile';
  public $version = 1.0;
  public $rext = array( 'rextFile' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeFileController.php',
    'view/RTypeFileView.php'
  );

  public $nameLocations = array(
    'es' => 'Fichero',
    'en' => 'File',
    'gl' => 'Ficheiro'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }


  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleDeploy();
  }
}
