<?php

Cogumelo::load("coreController/Module.php");
common::autoIncludes();
//form::autoIncludes();

//define('MOD_ADMIN_URL_DIR', 'blocks');

class Blocks extends Module
{

  public $name = "Blocks";
  public $version = 1.0;
  public $dependences = array(

  );


  public $includesCommon = array(
    //'view/BlocksPorto1.php'
  );



  public function __construct() {
    // $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/changepassword$#', 'view:AdminViewUser::changeUserPasswordForm' );
  }
}
