<script type="text/template" id="menuTermEditor">

  <div class="headSection clearfix">
    <div class="row">
      <div class="col-12 col-lg-6">
        <button type="button" class="navbar-gzz-toggle" >
            <span class="sr-only">Toggle navigation</span>
            <i class="fas fa-bars"></i>
        </button>
        <div class="headerTitleContainer">
          <h3>{t}Menu management{/t}</h3>
        </div>
      </div>
      <div class="col-12 col-lg-6 clearfix">
        <div class="headerActionsContainer">
          <button type="button" class="newTaxTerm btn btn-default"> {t}Add menu term{/t}</button>
          <span class="saveChanges">
            <button class="btn btn-danger cancelTerms">{t}Cancel{/t}</button>
            <button class="btn btn-primary saveTerms">{t}Save{/t}</button>
          </span>
        </div>
      </div>
    </div>
    <!-- /.navbar-header -->
  </div><!-- /headSection -->


  <div class="contentSection clearfix">
    <div class="admin-cols-8-4">
      <div class="row">
        <div class="col-12 col-xl-8">
          <div class="card">
            <div class="card-header">
                {t}List of menu terms{/t}
            </div>
            <div class="card-body">
              <div id="taxTermListContainer" class="gzznestable dd">
                <ol class="listTerms dd-list">
                </ol>
              </div>
            </div> <!-- end card-body -->
          </div> <!-- end card -->
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>

  </div><!-- /contentSection -->


  <div class="footerSection clearfix">
    <div class="headerActionsContainer">
      <button type="button" class="newTaxTerm btn btn-default">{t}Add term menu{/t}</button>
      <span class="saveChanges">
        <button class="btn btn-danger cancelTerms">{t}Cancel{/t}</button>
        <button class="btn btn-primary saveTerms">{t}Save{/t}</button>
      </span>
    </div>
  </div><!-- /footerSection -->

</script>


<script type="text/template" id="menuTermEditorItem">

  	<li class="dd-item " data-id="<%- term.id %>">
      <div class="dd-item-container clearfix">
        <div class="dd-content">
          <div class="nestableActions">
  	        <button class="btnEditTerm btn-icon btn-info" data-id="<%- term.id %>" ><i class="fas fa-pencil-alt fa-sm fa-fw"></i></button>
  	        <button class="btnDeleteTerm btn-icon btn-danger" data-id="<%- term.id %>" ><i class="fas fa-trash-alt fa-sm fa-fw"></i></button>
  	      </div>
    	  </div>
        <div class="dd-handle">
          <i class="fas fa-arrows-alt fa-fw icon-handle"></i>
          <%- term.name_{$cogumelo.publicConf.langDefault} %>
          <% if (term.resourceRelated){  %>
          <i class="fas fa-link fa-fw" aria-hidden="true"></i>
          <% } %>
          <% if (term.icon){  %>
            <img class="term-icon img-fluid" src="{$cogumelo.publicConf.mediaHost}cgmlImg/<%- term.icon %>-a<%- term.iconAKey %>/fast/">
          <% } %>
        </div>
      </div>
    </li>

</script>
