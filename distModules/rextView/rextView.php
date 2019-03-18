<?php
Cogumelo::load( 'coreController/Module.php' );


class rextView extends Module {

  public $name = 'rextView';
  public $version = 1.2;


  public $models = array(
    // 'RExtViewModel'
  );

  public $taxonomies = array(
    'viewAlternativeMode' => array(
      'idName' => 'viewAlternativeMode',
      'name' => array(
        'en' => 'viewAlternativeMode',
        'es' => 'viewAlternativeMode',
        'gl' => 'viewAlternativeMode'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        'none' => array(
          'idName' => 'none',
          'name' => array(
            'en' => 'None',
            'es' => 'Ninguno',
            'gl' => 'Ningún'
          )
        ),
        'tplEmpty' => array(
          'idName' => 'tplEmpty',
          'name' => array(
            'en' => 'Empty',
            'es' => 'Vacío',
            'gl' => 'Vacío'
          )
        ),
        'tplDefault' => array(
          'idName' => 'tplDefault',
          'name' => array(
            'en' => 'Default',
            'es' => 'Vista por defecto',
            'gl' => 'Vista por defecto'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtViewController.php'
  );


  public function __construct() {
    // error_log( 'rextView::__construct' );
    $this->loadViewAlternativeModeAppTerms();
  }


  public function loadViewAlternativeModeAppTerms() {
    // error_log( 'rextView::loadViewAlternativeModeAppTerms()' );
    $confFileAppTerms = Cogumelo::getSetupValue('setup:appBasePath').'/conf/inc/geozzyRExtViewAppTerms.php';
    if( file_exists( $confFileAppTerms ) ) {

      include( $confFileAppTerms );
      if( is_array( $rExtViewAppInitialTerms ) && count( $rExtViewAppInitialTerms ) > 0 ) {
        foreach( $rExtViewAppInitialTerms as $termInfo ) {
          if( strpos( $termInfo[ 'idName' ], 'tplApp' ) === 0 || strpos( $termInfo[ 'idName' ], 'viewApp' ) === 0 ) {
            $this->taxonomies['viewAlternativeMode']['initialTerms'][ $termInfo['idName'] ] = $termInfo;
          }
          else {
            Cogumelo::error( 'ERROR intentando usar un término ('.$termInfo[ 'idName' ].') como AlternativeModeAppTerm' );
          }
        }
      }
    }
    // error_log( 'rextView $this->taxonomies: ' . print_r( $this->taxonomies ,true ) );
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
