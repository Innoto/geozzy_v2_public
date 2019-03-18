

var routesMapInstanceCheck = false;
$( document ).ready(function() {
  routesMapInstanceCheck = geozzy.rExtMapInstance.url;
  geozzy.rExtMapInstance.onLoad(function(){
    var routeMap = geozzy.rExtMapInstance.resourceMap;
    if( typeof geozzy.rExtRoutesOptions != 'undefined' && typeof geozzy.rExtMapInstance != 'undefined' ) {
      google.maps.event.addListenerOnce(routeMap, 'idle', function() {
        rextRoutesJs.setRouteOnResourceMapInstance(routeMap);
      } );
    }
    else {
      cogumelo.log('Routes: resource id or MAP not found');
    }
  });
});

var rextRoutesJs = {
  setRouteOnResourceMapInstance: function(routeMap) {
    var that = this;
    // Set new map container height
    $( $(geozzy.rExtMapInstance.options.wrapper)[0] ).height( cogumelo.publicConf.rextRoutesConf.newMapHeight );

    // Desactivar como chegar en caso de existir
    if(
      typeof geozzy.rExtMapDirectionsController != 'undefined' &&
      typeof geozzy.rExtMapDirectionsController.mapClickEvent != 'undefined' &&
      typeof geozzy.rExtMapDirectionsController.mapClickEvent.remove != 'undefined'
    ){
      geozzy.rExtMapDirectionsController.mapClickEvent.remove();
    }

    var routesCollection = new geozzy.rextRoutes.routeCollection();

    geozzy.rExtMapInstance.resourceMarker.setMap(null);

    routesCollection.url = '/api/routes/id/' + geozzy.rExtRoutesOptions.resourceId;




    routesCollection.fetch( {
      success: function( res ) {
        if( geozzy.rExtMapInstance.url == routesMapInstanceCheck ) {
          var route = new geozzy.rextRoutes.routeView( {
            map: routeMap,
            routeModel: routesCollection.get( geozzy.rExtRoutesOptions.resourceId ),
            showGraph: geozzy.rExtRoutesOptions.showGraph,
            graphContainer: geozzy.rExtRoutesOptions.graphContainer
          });
        }
      }
    });
  }
};
