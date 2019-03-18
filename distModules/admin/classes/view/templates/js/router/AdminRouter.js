
var app = app || {};

var AdminRouter = Backbone.Router.extend({

  routes: {
    "" : "default",
    "403" : "accessDenied",
    "charts" : "charts",
    "multiList" : "multiList",
    "category/:id" : "categoryEdit",
    "category/:category/term/create" : "categoryNewTerm",
    "category/:category/term/edit/:term" : "categoryEditTerm",
    'menu': "menuEdit",
    "menu/term/create" : "menuNewTerm",
    "menu/term/edit/:term" : "menuEditTerm",
    "user/list" : "userList",
    "user/create" : "userCreate",
    "user/edit/id/:id" : "userEdit",
    "user/show" : "userShow",
    "role/list" : "roleList",
    "role/create" : "roleCreate",
    "role/edit/id/:id" : "roleEdit",

    "resourcepage/list": "resourcePageList",
    "resource/list": "resourceList",
    "comment/list": "commentList",
    "suggestion/list": "suggestionList",
    "topic/:id" : "resourceintopicList",
    "resourceintopic/list/topic/:id": "resourceintopicList",
    "resourceouttopic/list/topic/:id": "resourceouttopicList",
    "starred/:id": "starredList",
    "starred/star/:id/assign": "starredAssign",
    "resource/create(/star/:star)(/topic/:topicId)(/story/:storyId)/resourcetype/:resourcetype" : "resourceCreate",
    "resource/edit/id/:id(/topic/:topicId)(/type/:typeId)(/story/:storyId)" : "resourceEdit",
    "collection/create" : "collectionCreate",
    "collection/edit/:id" : "collectionEdit",
    //"stories/:id": "storiesList",
    "storysteps/:story(/topic/:topicId)": "storyStepsList",
    "storysteps/story/:id/assign": "storyStepsAdd",

    "translates/export/resources": "resourceExportView",
    "translates/export/collections": "collectionsExportView",
    "translates/import/files": "filesImportView",
  },

  default: function() {
    if(cogumelo.publicConf.mod_admin_defaultURL){
      app.router.navigate(cogumelo.publicConf.mod_admin_defaultURL, {trigger: true});
    }else{
      app.mainView.loadAjaxContent( '/admin/home' );
    }
  },

  accessDenied: function() {
    app.mainView.loadAjaxContent( '/admin/403' );
  },

  charts: function() {
    app.mainView.loadAjaxCharts( '/admin/charts' );
    app.mainView.setBodyClass('charts');
  },

  multiList: function() {
    app.mainView.loadAjaxContent( '/admin/multilist' );
    app.mainView.setBodyClass('multiList');
  },

  categoryEdit: function( id ){
    app.mainView.categoryEdit( id );
    app.mainView.menuSelect('category_'+id);
    app.mainView.setBodyClass('categoryEdit');
  },

  categoryNewTerm: function( category ){
    app.mainView.loadAjaxContent( '/admin/category/'+category+'/term/create');
    app.mainView.setBodyClass('categoryNewTerm');
  },

  categoryEditTerm: function( category, term ){
    app.mainView.loadAjaxContent( '/admin/category/'+category+'/term/edit/'+term);
    app.mainView.setBodyClass('categoryEditTerm');
  },

  menuEdit: function(){
    app.mainView.menuEdit();
    app.mainView.menuSelect('menu');
    app.mainView.setBodyClass('menuEdit');
  },

  menuNewTerm: function(){
    app.mainView.loadAjaxContent( '/admin/menu/term/create');
    app.mainView.setBodyClass('menuNewTerm');
  },

  menuEditTerm: function( term ){
    app.mainView.loadAjaxContent( '/admin/menu/term/edit/'+term);
    app.mainView.setBodyClass('menuEditTerm');
  },

  // User
  userList: function(){
    app.mainView.loadAjaxContent( '/admin/user/list' );
    app.mainView.menuSelect('user');
    app.mainView.setBodyClass('userList');
  },

  userCreate: function( ) {
    app.mainView.loadAjaxContent( '/admin/user/create');
    app.mainView.setBodyClass('userCreate');
  },

  userEdit: function( id ) {
    app.mainView.loadAjaxContent( '/admin/user/edit/id/' + id );
    app.mainView.setBodyClass('userEdit');
  },

  userShow: function() {
    app.mainView.loadAjaxContent( '/admin/user/show' );
    app.mainView.setBodyClass('userShow');
  },

  // Roles
  roleList: function(){
    app.mainView.loadAjaxContent( '/admin/role/list' );
    app.mainView.menuSelect('roles');
    app.mainView.setBodyClass('roleList');
  },

  roleCreate: function( ) {
    app.mainView.loadAjaxContent( '/admin/role/create');
    app.mainView.setBodyClass('roleCreate');
  },

  roleEdit: function( id ) {
    app.mainView.loadAjaxContent( '/admin/role/edit/id/' + id );
    app.mainView.setBodyClass('roleEdit');
  },

  commentList: function() {
    app.mainView.loadAjaxContent( '/admin/comment/list');
    app.mainView.menuSelect('comments');
    app.mainView.setBodyClass('commentList');
  },
  suggestionList: function() {
    app.mainView.loadAjaxContent( '/admin/suggestion/list');
    app.mainView.menuSelect('suggestions');
    app.mainView.setBodyClass('suggestionList');
  },

  // resources
  resourcePageList: function() {
    app.mainView.loadAjaxContent( '/admin/resourcepage/list');
    app.mainView.menuSelect('pages');
    app.mainView.setBodyClass('resourcePageList');
  },

  resourceList: function() {
    app.mainView.loadAjaxContent( '/admin/resource/list');
    app.mainView.menuSelect('contents');
    app.mainView.setBodyClass('resourceList');
  },

  resourceintopicList: function(id) {
    app.mainView.loadAjaxContent( '/admin/resourceintopic/list/topic/'+id);
    app.mainView.menuSelect('topic_'+id);
    app.mainView.setBodyClass('resourceintopicList');
  },

  resourceouttopicList: function(id) {
    app.mainView.loadAjaxContent( '/admin/resourceouttopic/list/topic/'+id);
    app.mainView.setBodyClass('resourceouttopicList');
  },

  starredList: function( id ){
    app.mainView.starredList( id );
    app.mainView.menuSelect('star_'+id);
    app.mainView.setBodyClass('starredList');

  },
  starredAssign: function(id) {
    app.mainView.loadAjaxContent( '/admin/starred/star/'+id+'/assign');
    app.mainView.setBodyClass('starredAssign');
  },

  resourceCreate:function(star, topic, story, resourcetype) {
    if (star !== null){
      app.mainView.loadAjaxContent( '/admin/resource/create/star/'+star+'/resourcetype/'+resourcetype);
    }
    else{
      if (topic !== null){
        app.mainView.loadAjaxContent( '/admin/resource/create/topic/'+topic+'/resourcetype/'+resourcetype);
      }
      else{
        if (story !== null){
          app.mainView.loadAjaxContent( '/admin/resource/create/story/'+story+'/resourcetype/'+resourcetype);
        }
        else{
          app.mainView.loadAjaxContent( '/admin/resource/create/resourcetype/'+resourcetype);
        }
      }
      app.mainView.setBodyClass('resourceCreate');
    }
  },

  resourceCreateinTopic:function( topic, resourcetype) {
    app.mainView.loadAjaxContent( '/admin/resource/create/'+topic+'/'+resourcetype);
    app.mainView.setBodyClass('resourceCreateinTopic');
  },
  resourceEdit:function( id, topic, type, story ) {
    if (topic !== null){
      app.mainView.loadAjaxContent( '/admin/resource/edit/resourceId/'+id+'/topic/'+topic);
    }
    else{
      if (type !== null){
        app.mainView.loadAjaxContent( '/admin/resource/edit/resourceId/'+id+'/type/'+type);
      }
      else{
        if (story !== null){
          app.mainView.loadAjaxContent( '/admin/resource/edit/resourceId/'+id+'/story/'+story);
        }
        else{
          app.mainView.loadAjaxContent( '/admin/resource/edit/resourceId/'+id);
        }
      }
    }
    app.mainView.setBodyClass('resourceEdit');
  },

  collectionCreate:function() {
    app.mainView.loadAjaxContent( '/admin/collection/create');
    app.mainView.setBodyClass('collectionCreate');
  },
  collectionEdit:function( id )   {
    app.mainView.loadAjaxContent( '/admin/collection/edit/collectionId/' + id);
    app.mainView.setBodyClass('collectionEdit');
  },

  storyStepsList: function( story ){
    app.mainView.loadAjaxContent( '/admin/storysteps/story/' + story);
    app.mainView.menuSelect('story_'+story);
    app.mainView.setBodyClass('storyStepsList');
  },

  storyStepsAdd: function(id) {
    app.mainView.loadAjaxContent( '/admin/storysteps/story/' + id +'/assign');
    app.mainView.setBodyClass('storyStepsAdd');
  },

  resourceExportView: function() {
    app.mainView.loadAjaxContent( '/admin/translates/export/resources');
    app.mainView.menuSelect('transExpRes');
    app.mainView.setBodyClass('translatesExport');
  },

  collectionsExportView: function() {
    app.mainView.loadAjaxContent( '/admin/translates/export/collections');
    app.mainView.menuSelect('transExpColl');
    app.mainView.setBodyClass('translatesExport');
  },

  filesImportView: function() {
    app.mainView.loadAjaxContent( '/admin/translates/import/files');
    app.mainView.menuSelect('transImpFiles');
    app.mainView.setBodyClass('translatesImport');
  },

});
