<?php
geozzy::load('view/RTypeViewCore.php');


class RTypeBlogView extends RTypeViewCore implements RTypeViewInterface {

  public function __construct( $defResCtrl = null ) {
    // error_log( 'RTypeBlogView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct( $defResCtrl, new rtypeBlog() );
  }

} // class RTypeAppBlogView
