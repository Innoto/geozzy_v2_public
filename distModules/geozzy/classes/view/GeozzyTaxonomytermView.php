<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
form::autoIncludes();
filedata::autoIncludes();
user::autoIncludes();


class GeozzyTaxonomytermView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return true;
  }




  /**
   * Form fields and validations
   *
   * @return object
   **/
  public function taxtermFormDefine( $request ) {
    $langAvailable = false;
    $this->template->assign( 'JsLangAvailable', 'false' );
    $this->template->assign( 'JsLangDefault', 'false' );

    $langsConf = Cogumelo::getSetupValue( 'lang:available' );
    if( is_array( $langsConf ) ) {
      $langAvailable = array_keys( $langsConf );
      $langDefault = Cogumelo::getSetupValue( 'lang:default' );
      $tmp = implode( "', '", $langAvailable );
      $this->template->assign( 'JsLangAvailable', "['".$tmp."']" );
      $this->template->assign( 'JsLangDefault', "'".$langDefault."'" );
    }


    $form = new FormController( 'taxtermForm', '/categories/sendcategoriesform' ); //actionform

    $form->setSuccess( 'redirect', '/' );

    $fieldsInfo = array(
      'id' => array(
        'params' => array( 'type' => 'reserved', 'value' => null )
      ),
      'idName' => array(
        'params' => array( 'label' => __('ID Name (WARNING)'), 'value' => null )
      ),
      'taxgroup' => array(
        'params' => array( 'type' => 'reserved', 'value' => $request['1'] )
      ),
      'icon' => array(
        'params' => array( 'type' => 'file', 'placeholder' => __('Upload an image'),
          'id' => 'taxonomyTermIconFile', 'label' => __('Upload an image'),
          'destDir' => TaxonomytermModel::$cols['icon']['uploadDir'] ),
        'rules' => [ 'accept' => 'image/jpeg,image/png,image/svg*', 'maxfilesize' => '2097152' ]
      ),
      'name' => array(
        'translate' => true,
        'params' => array( 'label' => __('Name') )
      ),
      'mediumDescription' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Medium description' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => '1000' )
      )
    );

    // $this->arrayToForm( $form, $fieldsInfo, $langAvailable );
    $form->definitionsToForm( $fieldsInfo );

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => 'Save', 'class' => 'gzzAdminToMove' ) );

    /* VALIDATIONS */
    $form->setValidationRule( 'name_'.$langDefault, 'required' );

    $useraccesscontrol = new UserAccessController();
    $onlySA = $useraccesscontrol->checkPermissions('only:SA');
    if( !$onlySA ) {
      $form->setFieldParam( 'idName', 'type', 'reserved' );
    }

    if(isset($request[2])){
      $taxtermModel = new TaxonomytermModel();
      $dataVO = $taxtermModel->listItems( array( 'filters' => [ 'id' => $request[2] ], 'affectsDependences' => [ 'FiledataModel' ] ) )->fetch();

      $taxtermData = $dataVO->getAllData();
      $taxtermData = $taxtermData['data'];

      $fileDep = $dataVO->getterDependence( 'icon' );

      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $fileData = $fileModel->getAllData();
          $taxtermData[ 'icon' ] = $fileData[ 'data' ];
        }
      }
      $form->loadArrayValues($taxtermData);
    }
    return $form;
  }


  /**
   * Returns necessary block form
   *
   * @param $form
   *
   * @return string
   **/
  public function taxtermGetFormBlock( $form ) {
    $form->saveToSession();

    $this->template->assign("taxtermFormOpen", $form->getHtmpOpen());
    $this->template->assign("taxtermFormFieldsArray", $form->getHtmlFieldsArray() );
    $this->template->assign("taxtermFormFields", $form->getHtmlFieldsAndGroups());
    $this->template->assign("taxtermFormClose", $form->getHtmlClose());
    $this->template->assign("taxtermFormValidations", $form->getScriptCode());

    $this->template->setTpl('taxtermForm.tpl', 'geozzy');

    return $this->template;
  }



  /**
   * Returns necessary html form
   *
   * @param $form
   *
   * @return string
   **/
  public function taxtermFormGet( $form ) {

    $templateBlock = $this->taxtermGetFormBlock( $form );
    return $templateBlock->execToString();
  }


  /**
   * Action sendTaxtermForm
   *
   * @return void
   **/
  public function sendTaxtermForm() {
    $form = $this->actionTaxTermForm();
    $this->taxtermOk($form);

    echo $form->getJsonResponse();
  }


  /**
   * Assigns the forms validations
   *
   * @return $form
   **/
  public function actionTaxTermForm() {
    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
    }
    return $form;
  }


  /**
   * Edit/Create taxterm
   *
   * @return $taxterm
   **/
  public function taxtermOk( $form ) {
    $langDefault = Cogumelo::getSetupValue( 'lang:default' );

    //Si tod0 esta OK!
    if( !$form->processFileFields() ) {
      $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea necesario subirlos otra vez.', 'formError' );
    }
    $taxterm = false;
    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();

      $taxterm = new TaxonomytermModel( $valuesArray );
      $taxterm->save();

      // $saveResult = false;
      // $affectsDependences = false;

      $imageFile = $form->getFieldValue( 'icon' );
      if( !$form->existErrors() && isset( $imageFile['status'] ) ) {

        $filedataCtrl = new FiledataController();
        $newFiledataObj = false;

        switch( $imageFile['status'] ) {
          case 'LOADED':
            $imageFileValues = $imageFile['values'];
            $newFiledataObj = $filedataCtrl->createNewFile( $imageFileValues );
            // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
            if( $newFiledataObj ) {
              $taxterm->setter( 'icon', $newFiledataObj->getter( 'id' ) );
            }
            break;
          case 'REPLACE':
            // error_log( 'To Model - fileInfoPrev: '. print_r( $imageFile[ 'prev' ], true ) );
            $imageFileValues = $imageFile['values'];
            $prevFiledataId = $taxterm->getter( 'icon' );
            $newFiledataObj = $filedataCtrl->createNewFile( $imageFileValues );
            // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
            if( $newFiledataObj ) {
              $taxterm->setter( 'icon', $newFiledataObj->getter( 'id' ) );
              // error_log( 'To Model - deleteFile ID: '.$prevFiledataId );
              $filedataCtrl->deleteFile( $prevFiledataId );
            }
            break;
          case 'DELETE':
            if( $prevFiledataId = $taxterm->getter( 'icon' ) ) {
              // error_log( 'To Model - prevFiledataId: '.$prevFiledataId );
              $filedataCtrl->deleteFile( $prevFiledataId );
              $taxterm->setter( 'icon', null );
            }
            break;
          case 'EXIST':
            $imageFileValues = $imageFile[ 'values' ];
            if( $prevFiledataId = $taxterm->getter( 'icon' ) ) {
              // error_log( 'To Model - UPDATE prevFiledataId: '.$prevFiledataId );
              $filedataCtrl->updateInfo( $prevFiledataId, $imageFileValues );
            }
            break;
          default:
            // error_log( 'To Model: DEFAULT='.$imageFile['status'] );
            break;
        }


        /*
          switch( $imageFile['status'] ) {
            case 'LOADED':
              error_log( 'To Model: '.$imageFile['status'] );
              $fileInfo = $imageFile[ 'values' ];
              error_log( 'To Model - fileInfo: '. print_r( $fileInfo, true ) );
              $affectsDependences = true;
              $taxterm->setterDependence( 'icon', new FiledataModel( $fileInfo ) );
              break;
            case 'REPLACE':
              error_log( 'To Model: '.$imageFile['status'] );
              $fileInfoPrev = $imageFile[ 'prev' ];
              $fileInfoNew = $imageFile[ 'values' ];
              error_log( 'To Model - fileInfoPrev: '. print_r( $fileInfoPrev, true ) );
              error_log( 'To Model - fileInfoNew: '. print_r( $fileInfoNew, true ) );
              $affectsDependences = true;
              // TODO: Falta eliminar o ficheiro anterior
              $taxterm->setterDependence( 'icon', new FiledataModel( $fileInfoNew ) );
              break;
            case 'DELETE':
              error_log( 'To Model: '.$imageFile['status'] );
              $fileInfo = $imageFile[ 'prev' ];
              error_log( 'To Model - fileInfo: '. print_r( $fileInfo, true ) );
              // Apaño
              $taxterm->setter( 'icon', null );
              // PENDIENTE
              // $affectsDependences = true;
              // $taxterm->setterDependence( 'icon', new FiledataModel( $imageFile['values'] ) );
              break;
            case 'EXIST':
              error_log( 'To Model: '.$imageFile['status'] );
              break;
            default:
              error_log( 'To Model: DEFAULT='.$imageFile['status'] );
              break;
          }
        */

      }

      // $taxterm->save( array( 'affectsDependences' => $affectsDependences ) );

      if( array_key_exists('relTermMenuRes', $valuesArray) ){
        $resTaxTermModel = new ResourceTaxonomytermModel();
        $resTaxTermObj = $resTaxTermModel->listItems( ['filters' => [ 'taxonomyterm' => $taxterm->getter('id') ] ]);
        if(is_object($resTaxTermObj)){
          $resTermItem = $resTaxTermObj->fetch();
        }
        if( empty($valuesArray['relTermMenuRes'])){
          if(!empty($resTermItem)){
            //Delete
            $resTermItem->delete();
          }
        }
        else{
          if(!empty($resTermItem)){
            //Update
            $resTermItem->setter( 'resource', $valuesArray['relTermMenuRes'] );
            $resTermItem->save();
          }
          else{
            //Create
            $newResTaxTermModel = new ResourceTaxonomytermModel([ 'taxonomyterm' => $taxterm->getter('id'), 'resource' => $valuesArray['relTermMenuRes'] ]);
            $newResTaxTermModel->save();
          }
        }
      }
      if( empty($taxterm->getter('idName')) ){
        $taxterm->setter( 'idName', 'term-'.$taxterm->getter('id') );
      }


      $taxterm->save();
    }
    return $taxterm;
  }


  // /**
  //  * Crea los campos y les asigna las reglas en form
  //  *
  //  * @param $form
  //  * @param $form
  //  * @param $form
  // **/
  // public function arrayToForm( $form, $fieldsInfo, $langAvailable ) {
  //   foreach( $fieldsInfo as $fieldName => $definition ) {
  //     if( !isset( $definition['params'] ) ) {
  //       $definition['params'] = false;
  //     }
  //     if( isset( $definition['translate'] ) && $definition['translate'] === true ) {
  //       $baseClass = '';
  //       if( isset( $definition['params']['class'] ) &&  $definition['params']['class'] !== '' ) {
  //         $baseClass = $definition['params']['class'];
  //       }
  //       foreach( $langAvailable as $lang ) {
  //         $definition['params']['class'] = $baseClass . ' js-tr js-tr-'.$lang;
  //         $form->setField( $fieldName.'_'.$lang, $definition['params'] );
  //         if( isset( $definition['rules'] ) ) {
  //           foreach( $definition['rules'] as $ruleName => $ruleParams ) {
  //             $form->setValidationRule( $fieldName.'_'.$lang, $ruleName, $ruleParams );
  //           }
  //         }
  //       }
  //     }
  //     else {
  //       $form->setField( $fieldName, $definition['params'] );
  //       if( isset( $definition['rules'] ) ) {
  //         foreach( $definition['rules'] as $ruleName => $ruleParams ) {
  //           $form->setValidationRule( $fieldName, $ruleName, $ruleParams );
  //         }
  //       }
  //     }
  //   }
  // }


}
