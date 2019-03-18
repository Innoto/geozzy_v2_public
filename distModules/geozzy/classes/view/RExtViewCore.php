<?php
interface RExtViewInterface {
  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck();

  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form );

  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId );
}



Cogumelo::load('coreView/View.php');

class RExtViewCore extends View {

  public $defResCtrl = null;
  public $defRTypeCtrl = null;
  public $rExtModule = null;
  public $rExtCtrl = null;
  public $rExtName = 'RExtNameUnknown';
  public $prefix = 'rExt_';

  public function __construct( RTypeController $defRTypeCtrl, Module $rExtModule, $prefix = false ) {
    // error_log( 'RExtViewCore: __construct() para '.$rExtModule->name.' - '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

    if( $defRTypeCtrl ) {
      $this->defRTypeCtrl = $defRTypeCtrl;
      $this->defResCtrl = $defRTypeCtrl->defResCtrl;
    }
    $rExtName = $this->rExtName = $rExtModule->name;
    $this->prefix = ( $prefix ) ? $prefix : $rExtName.'_';

    parent::__construct();

    $rExtCtrlClassName = 'RE'.mb_strcut( $rExtName, 2 ).'Controller';
    $rExtName::load( 'controller/'.$rExtCtrlClassName.'.php' );
    $this->rExtCtrl = new $rExtCtrlClassName( $defRTypeCtrl );
  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {
    // error_log( 'RExtViewCore: accessCheck() para '.$this->rExtName );

    return true;
  }


  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    // error_log( __CLASS__.': getFormBlockInfo( $form ) para '.$this->rExtName );

    $formBlockInfo = $this->rExtCtrl->getFormBlockInfo( $form );

    return $formBlockInfo;
  }


  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( __CLASS__.': getViewBlockInfo('.$resId.') para '.$this->rExtName );

    $rExtViewBlockInfo = $this->rExtCtrl->getViewBlockInfo( $resId );

    return $rExtViewBlockInfo;
  }

} // class RExtViewCore extends View
