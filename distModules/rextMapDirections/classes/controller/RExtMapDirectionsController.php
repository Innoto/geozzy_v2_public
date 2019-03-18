<?php
geozzy::load( 'controller/RExtController.php' );

class RExtMapDirectionsController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextMapDirections(), 'rExtMapDirections_' );
  }


  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    // error_log( "RExtMapDirectionsController: getRExtData( $resId )" );
    $rExtData = false;

    // error_log( 'RExtMapDirectionsController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtMapDirectionsController: manipulateForm()" );
    $rExtFieldNames = array();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  // parent::getFormBlockInfo( $form );



  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  // parent::resFormRevalidate( $form );



  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtMapDirectionsController: resFormProcess()" );
  }



  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )


  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    $resData = $this->defResCtrl->getResourceData();

    $rExtViewBlockInfo['data']['title'] = $resData['title'];

    if( isset( $resData['locLat'] ) && $resData['locLat'] !== '' ) {
      $rExtViewBlockInfo['data']['locLat'] = $resData['locLat'];
      $rExtViewBlockInfo['data']['locLon'] = $resData['locLon'];
      $rExtViewBlockInfo['data']['defaultZoom'] = $resData['defaultZoom'];
    }
    else {
      $rExtViewBlockInfo['data']['locLat'] = 0;
      $rExtViewBlockInfo['data']['locLon'] = 0;
      $rExtViewBlockInfo['data']['defaultZoom'] = 0;
    }

      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextMapDirections' );


    return $rExtViewBlockInfo;
  }

} // class RExtMapDirectionsController
