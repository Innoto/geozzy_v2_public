
<nav id="menu-wrapper" class="clearfix" role="navigation">
  <div class="sidebar" role="navigation">
    <div class="sidebar-nav">
        <div id="menuInfo">
          <div class="menuLogo">
            <a href="/">
              {if !isset($logoCustom)}
                <img src="{$cogumelo.publicConf.media}/module/geozzy/img/logo.png" class="img-fluid">
              {else}
                <img src="{$cogumelo.publicConf.media}{$logoCustom}" class="img-fluid">
              {/if}
            </a>
          </div>
          <div class="userInfo">
            <div class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                {if array_key_exists('avatar', $user['data'])}
                  <img class="userAvatar" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$user['data']['avatar']}-a{$user['data']['avatarAKey']}/fast_cut/{$user['data']['avatarName']}">
                {/if}
                {$user['data']['login']}
              </a>
              <div class="dropdown-menu dropdown-user">
                {if $userPermission}
                  <a class="dropdown-item" href="/admin#user/show"><i class="fas fa-user fa-sm fa-fw"></i> {t}User Profile{/t}</a></li>
                  <a class="dropdown-item" href="/admin#user/edit/id/{$user['data']['id']}"><i class="fas fa-edit fa-sm fa-fw"></i> {t}Edit Profile{/t}</a></li>
                {/if}
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/admin/logout"><i class="fas fa-sign-out-alt fa-sm fa-fw"></i>{t}Logout{/t}</a>
              </div>
              <!-- /.dropdown-user -->
            </div>
          </div>
        </div>
        <!-- Navigation -->
        <ul class="navbar-nav clearfix" id="side-menu">
          <!-- TOPIC -->
          <script type="text/template" id="menuTopics">
          {if $topicPermission}
          <% _.each(topics, function(topic) { %>
            <li class="topics topic_<%- topic.id %>">
              <a class="" href="/admin#topic/<%- topic.id %>"><i class="fas fa-star fa-sm fa-fw"></i> <%- topic.name_{$cogumelo.publicConf.langDefault} %> </a>
            </li>
          <% }); %>
          {/if}
          </script>
          <!-- END TOPICS -->
          {if $superAdminPermission}
            <li class="contents">
              <a href="/admin#resource/list"><i class="fas fa-indent fa-sm fa-fw"></i> {t}Contents{/t} </a>
            </li>
          {/if}

          {if isset($biInclude)}
            <li class="charts">
              <a href="/admin#charts"><i class="fas fa-chart-line fa-sm fa-fw"></i> {t}Charts{/t}</a>
            </li>
          {/if}

          {if $pagePermission}
          <li class="pages">
            <a href="/admin#resourcepage/list"><i class="far fa-copy fa-sm fa-fw"></i> {t}Pages{/t} </a>
          </li>
          {/if}

          <li class="starred">
            <a href="/admin#starred"><i class="fas fa-star fa-sm fa-fw"></i> {t}Starred{/t} <span class="fas arrow fa-fw"></span></a>
            <ul class="nav-second-level starredList">
               <!-- TOPIC -->
              <script type="text/template" id="menuStarred">
              <% _.each(starred, function(star) { %>
               <li class="starred star_<%- star.id %>">
                  <a href="/admin#starred/<%- star.id %>"><i class="fas fa-star fa-sm fa-fw"></i> <%- star.name_{$cogumelo.publicConf.langDefault} %> </a>
               </li>
              <% }); %>
              </script>
              <!-- END TOPICS -->
            </ul>
          </li>

          <li class="menu">
            <a href="/admin#menu"><i class="fas fa-bars fa-sm fa-fw" aria-hidden="true"></i> {t}Menu{/t} </a>
          </li>

          <!-- Categories -->
          <li class="categories">
            <a href="#"><i class="fas fa-tags fa-sm fa-fw"></i> {t}Categories{/t} <span class="fas arrow fa-fw"></span></a>
              <ul class="nav-second-level categoriesList">
                <script type="text/template" id="menuCategoryElement">
                  <% for(var categoryK in categories) { %>
                    <li class="  category_<%- categories[categoryK].id %>">
                      <a class="  " href="/admin#category/<%- categories[categoryK].id %>"><i class="fas fa-tag fa-sm fa-fw"></i> <%- categories[categoryK].name_{$cogumelo.publicConf.langDefault} %> </a>
                    </li>
                  <% } %>
                </script>
              </ul>
              <!-- /.nav-second-level -->
          </li>

          {if $superAdminPermission}
            <li class="translates">
              <a href="#"><i class="fas fa-exchange-alt fa-sm fa-fw"></i> {t}Translates{/t} <span class="fas arrow fa-fw"></span></a>
              <ul class="nav-second-level">
                <li class="transExport">
                  <a href="#"><i class="fas fa-long-arrow-alt-left fa-sm fa-fw"></i> {t}Export{/t} <span class="fas arrow fa-fw"></span></a>
                  <ul class="nav-third-level">
                    <li class="transExpRes"><a href="/admin#translates/export/resources"> {t}Resources{/t}</a></li>
                    <li class="transExpColl"><a href="/admin#translates/export/collections"> {t}Collections{/t}</a></li>
                  </ul>
                </li>
                <li class="transImport">
                  <a href="#"><i class="fas fa-long-arrow-alt-right fa-sm fa-fw"></i> {t}Import{/t} <span class="fas arrow fa-fw"></span></a>
                  <ul class="nav-third-level">
                    <li class="transImpFiles"><a href="/admin#translates/import/files"> {t}Files{/t}</a></li>
                  </ul>
                </li>
              </ul>
            </li>
          {/if}
          {if isset($rextCommentInclude)}
            <li class="comments">
              <a href="/admin#comment/list"><i class="fas fa-comments-o fa-sm fa-fw"></i> {t}Comments{/t}</a>
            </li>
          {/if}

          {if isset($rextCommentInclude)}
            <li class="suggestions">
              <a href="/admin#suggestion/list"><i class="fas fa-commenting-o fa-sm fa-fw"></i> {t}Suggestions{/t}</a>
            </li>
          {/if}

          <!-- User -->
          {if $userPermission}
          <li>
            <a href="#"><i class="fas fa-users fa-sm fa-fw"></i> {t}Users{/t} <span class="fas arrow fa-fw"></span></a>
              <ul class="  nav-second-level">
                <li class="user">
                  <a href="/admin#user/list"><i class="fas fa-user fa-sm fa-fw"></i> {t}User{/t} </a>
                </li>
                <li class="roles">
                  <a href="/admin#role/list"><i class="fas fa-tag fa-sm fa-fw"></i> {t}Roles{/t}</a>
                </li>

              </ul>
              <!-- /.nav-third-level -->
          </li>
          {/if}

        </ul> <!-- /side-menu -->
    </div>
  </div>
</nav>


<a href="#" class="btn btn-primary pull-left" id="menu-toggle">
  <!--<i class="fas fa-bars fa-sm" aria-hidden="true"></i>-->
  <i class="fas fa-angle-double-left fa-sm opened" aria-hidden="true"></i>
  <i class="fas fa-angle-double-right fa-sm closed" aria-hidden="true"></i>
</a>
