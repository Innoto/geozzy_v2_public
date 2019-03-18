<?php


class RoutesController {

  public function __construct() {
    $this->cacheQuery = true;

  }



  public function extractPoints( $geom ) {
    $points = array();

    foreach( $geom->getComponents() as $comp ) {
      if( $comp->getGeomType() == 'LineString' ) {
        //$points = array_merge( $points , $this->extractPoints( $comp ) );
      }
      else
      if( $comp->getGeomType() == 'Point' ) {

        if( $comp->z() ) {
          $points[] = [ $comp->y(), $comp->x(), round($comp->z(), 1) ];
        }
        else {
          $points[] = [ $comp->y(), $comp->x(), false ];
        }

        //array_push( $points , get_class_methods($comp) );
        //$points[] = [$comp->getX() , $comp->getY()];
      }
    }

    return $points;
  }


  public function getRouteInForm( $idForm ) {
    $route = [];
    $useraccesscontrol = new UserAccessController();
    if( $useraccesscontrol->checkPermissions('resource:create', 'admin:full') || $useraccesscontrol->checkPermissions('resource:edit', 'admin:full') ) {
      form::autoIncludes();
      $formRoute = new FormController( );
      $formRoute->loadFromSession( $idForm );



      if( isset($formRoute->getFieldValue('rExtRoutes_routeFile')['temp']) ) {
        $filePath = $formRoute->getFieldValue('rExtRoutes_routeFile')[ 'temp' ]['absLocation'];
      }
      else {
        $filePath = Cogumelo::getSetupValue( 'mod:filedata' )['filePath'].  $formRoute->getFieldValue('rExtRoutes_routeFile')[ 'prev' ]['absLocation'];
      }



      try {

        $fnSplited = explode( '.', $filePath );
        $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );
        /*echo "<pre>";
        var_dump( $polygon->getGeomType() );
        echo "<br>--------------------<br>";
        var_dump( json_encode( $this->extractPoints( $polygon )) );*/
        $cent = $polygon->getCentroid();
        $start = $polygon->startPoint();
        $end = $polygon->endPoint();

        $route['id'] =  1;
        $route['circular'] = 0;
        $route['centroid'] =  [ $cent->y(), $cent->x() ];
        $route['start'] =  [ $start->y(), $start->x() ];
        $route['end'] =  [ $end->y(), $end->x() ];
        $route['trackPoints'] = $this->extractPoints( $polygon );
      }
      catch(Exception $e) {
          Cogumelo::error( $e->getMessage() );
      }
    }


    return [$route];
  }




  public function getRoute( $ids, $resolution = 100 ) {
    rextRoutes::autoIncludes();
    $useraccesscontrol = new UserAccessController();

    $route = false;

    $f = array();
    $f['ResourceModel.id'] = $ids;


    if(! ($useraccesscontrol->checkPermissions('resource:create', 'admin:full') || $useraccesscontrol->checkPermissions('resource:edit', 'admin:full')) ) {
      $f['ResourceModel.published'] = 1;
    }


    $routesModel = new RoutesModel();
    $routesList = $routesModel->listItems( array(
      'joinType'=>'RIGHT',
      'affectsDependences'=> array('ResourceModel'),
      'filters' => $f,
      'cache' => $this->cacheQuery
    ));

    $routes = [];

    while( $routeVO = $routesList->fetch() ) {

      $route = [ ];

      if(  $routeVO->getter('routeFile') ) {

        $fileDataList = (new FiledataModel(['id'=> $routeVO->getter('routeFile') ]) )->save();
        $filePath = Cogumelo::getSetupValue( 'mod:filedata' )['filePath'] . $fileDataList->getter('absLocation');
      }
      else {
        $filePath = false;
      }


      if ( file_exists( $filePath ) ) {

        $fnSplited = explode( '.', $filePath );
        /*array_pop( $fnSplited )*/

        try {
          $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );
          /*echo "<pre>";
          var_dump( $polygon->getGeomType() );
          echo "<br>--------------------<br>";
          var_dump( json_encode( $this->extractPoints( $polygon )) );*/
          $cent = $polygon->getCentroid();


          $route['id'] =  $routeVO->getter('resource');

          $route['circular'] = $routeVO->getter('circular');
          $route['centroid'] =  [ $cent->y(), $cent->x() ];
          $route['trackPoints'] = $this->simplifyPoints(  $polygon, $resolution );
        }
        catch(Exception $e) {
            Cogumelo::error( $e->getMessage() );
        }
      }
      else {
        Cogumelo::log('Route file not found: '. $filePath);
      }

      $routes[] = $route;

    }


    return $routes;
  }


  private function simplifyPoints($polygon, $resolution) {
    $pointsFinal = [];
    $points = $this->extractPoints( $polygon );
    $cent = $polygon->centroid();
    $centroid = [ $cent->y(), $cent->x() ];


    $minX = $centroid[0];
    $minY = $centroid[1];
    $maxX = $centroid[0];
    $maxY = $centroid[1];



    if( count($points) ) {

      // get max and min
      foreach( $points as $pk => $point ) {
        if( $point[0] < $minX ) {
          $minX = $point[0];
        }
        else
        if( $point[0] > $minX ) {
          $maxX = $point[0];
        }

        if( $point[1] < $minY ) {
          $minY = $point[1];
        }
        else
        if( $point[1] > $minY ) {
          $maxY = $point[1];
        }

      }

      // distance beetwen max and min coordinates
      $geomMaxDist = $this->distanceAB( [$minX,$minY], [$maxX,$maxY] );
      $admisibleDistBetweenPoints =  $geomMaxDist - (($resolution  )/100) * $geomMaxDist;



      $previusPoint = false;
      foreach( $points as $pk => $point ) {

        /*if( $pk == sizeof($points)-1 ) {
          $previusPoint = false;
        }*/

        if($previusPoint) {
          //$points[$pk][2] = $this->distanceAB( $previusPoint, $point  );
          //$points[$pk][3] = $geomMaxDist;
          //$points[$pk][3] = $this->distanceAB( $previusPoint, $point  );
          //$points[$pk][4] = $admisibleDistBetweenPoints;

          if( $this->distanceAB( $previusPoint, $point  ) > $admisibleDistBetweenPoints ) {
            $pointsFinal[] = $point;
            $previusPoint = $point;
          }
        }
        else {
          $pointsFinal[] = $point;
          $previusPoint = $point;
        }
      }

      // allways set final point
      $pointsFinal[ count($pointsFinal) - 1 ] = $point;
    }

    return $pointsFinal;
  }

  private function distanceAB( $coorA, $coorB ) {
    $distance = 0;

    $delta_lat = $coorB[0] - $coorA[0];
    $delta_lon = $coorB[1] - $coorA[1];

    $earth_radius = 6372.795477598;

    $alpha    = $delta_lat/2;
    $beta     = $delta_lon/2;
    $a        = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($coorB[1])) * cos(deg2rad( $coorA[1])) * sin(deg2rad($beta)) * sin(deg2rad($beta)) ;
    $c        = asin(min(1, sqrt($a)));
    $distance = 2*$earth_radius * $c;


    return $distance;
  }


  public function validateRoute( $filePath ) {
    rextRoutes::autoIncludes();

    $isValid = false;
    //$filePath = '/home/pblanco/Descargas/Felechosa_final.gpx';

    if ( file_exists( $filePath ) ) {

      $fnSplited = explode( '.', $filePath );
      /*array_pop( $fnSplited )*/

      try {
        $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );
        /*echo "<pre>";
        var_dump( $polygon->getGeomType() );
        echo "<br>--------------------<br>";
        var_dump( json_encode( $this->extractPoints( $polygon )) );*/
        $isValid = true;
      }
      catch(Exception $e) {
          Cogumelo::error( $e->getMessage() );
      }
    }
    else {
      Cogumelo::log('Route file not found: '. $filePath);
    }


    return $route;
  }

}
