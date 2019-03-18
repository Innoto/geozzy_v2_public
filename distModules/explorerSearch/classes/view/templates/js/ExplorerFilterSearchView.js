var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSearchView = geozzy.filterView.extend({
  searchStr: '',
  serverResponse: false,
  initialize: function( opts ) {
    var that = this;
    var options = {
      template: ''+
        '<div class="input-group">'+
          '<input type="text" placeholder="'+__('Search')+'">'+
          '<span class="btnGroup">'+
            '<button class="search btn btn-default"><i class="fas fa-search" aria-hidden="true"></i></button>'+
            '<button class="clear btn btn-default" style="display:none;"><i class="fas fa-times-circle" aria-hidden="true"></i></button>'+
          '</span>'+
        '</div>',
      containerClass: false,
      onChange: function(){}
    };
    that.options = $.extend(true, {}, options, opts);

  },

  filterAction: function( model ) {
    var that = this;
    var ret = true;

    if( that.searchStr != '' && that.serverResponse.length > 0 ) {
      if( $.inArray( model.get('id'), that.serverResponse ) != -1) {
        ret = true;
      }
      else {
        ret = false;
      }
    }

    return ret;
  },

  render: function() {
    var that = this;


    $(that.options.containerClass).html(that.options.template);

     $(that.options.containerClass + ' input').on('keyup', function(e){
      if(e.keyCode == 13) {
        that.searchFind();
      }
    });

    $(that.options.containerClass).find('.search').on('click', function() {
      that.searchFind();
    });

    $(that.options.containerClass).find('.clear').on('click', function() {
      that.reset();
    });
  },

  searchFind: function() {
    var that = this;

    $(that.options.containerClass).find('button.search').hide();
    $(that.options.containerClass).find('button.clear').show();

    that.searchStr = $(that.options.containerClass + ' input').val() ;

    $.post(
      '/api/explorerSearch',
      {searchString: that.searchStr},
      function( data ) {
        that.serverResponse = data;
        that.options.onChange();
        that.parentExplorer.applyFilters();
      }
    );
  },

  reset: function() {
    var that = this;
    that.searchStr = '';

    $(that.options.containerClass + ' input').val('');
    that.searchFind();
    $(that.options.containerClass).find('button.search').show();
    $(that.options.containerClass).find('button.clear').hide();

  }

});
