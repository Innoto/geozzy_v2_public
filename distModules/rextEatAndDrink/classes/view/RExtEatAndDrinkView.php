<?php
geozzy::load('view/RExtViewCore.php');


class RExtEatAndDrinkView extends RExtViewCore implements RExtViewInterface {

  public function __construct( RTypeController $defRTypeCtrl = null ) {
    // error_log( __CLASS__.': __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct( $defRTypeCtrl, new rextEatAndDrink() );
  }

} // class extends RExtViewCore