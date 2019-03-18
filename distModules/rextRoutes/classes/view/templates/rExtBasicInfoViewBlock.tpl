<div class="rExtRoutesBasic">
  <div class="routeInfo">

    {if !empty($rExt.data.routeStart)}
      <div class="itemRoute routeStart">
        <div class="icon">
          <i class="far fa-flag fa-fw"></i>
        </div>
        <div class="text">{$rExt.data.routeStart}</div>
      </div>
    {/if}

    {if !empty($rExt.data.routeEnd)}
      <div class="itemRoute routeEnd">
        <div class="icon">
          <i class="fas fa-flag-checkered"></i>
        </div>
        <div class="text">{$rExt.data.routeEnd}</div>
      </div>
    {/if}

    {if !empty($rExt.data.travelDistanceKm)}
      <div class="itemRoute routeTravelDistance duration">
        <div class="icon">
          <i class="fas fa-map-signs fa-fw"></i>
        </div>
        <div class="text">{$rExt.data.travelDistanceKm} Km</div>
      </div>
    {/if}

    {if $rExt.data.durationHours !== '0' || $rExt.data.durationMinutes !== '00'}
      <div class="itemRoute routeTravelTime duration">
        <div class="icon">
          <i class="far fa-clock fa-fw"></i>
        </div>
        <div class="text">
          {if $rExt.data.durationHours !== '0'}
            <span>{$rExt.data.durationHours} h</span>
          {/if}
          {if $rExt.data.durationMinutes !== '00'}
            <span>{$rExt.data.durationMinutes} min</span>
          {/if}
        </div>
      </div>
    {/if}

    <div class="itemRoute routeType">
      <div class="title">{if $rExt.data.circular}{t}Circular route{/t}{else}{t}Lineal route{/t}{/if}</div>
      <div class="icon {if $rExt.data.circular}circular{else}lineal{/if}">
        {if $rExt.data.circular}
          <i class="fas fa-undo-alt fa-flip-horizontal fa-fw"></i>
        {else}
          <i class="fas fa-long-arrow-alt-right fa-fw"></i>
        {/if}
      </div>
    </div>

    {if !empty($rExt.data.slopeUp) || !empty($rExt.data.slopeDown)}
      <div class="itemRoute routeSlope">
        <div class="title">{t}Slopes{/t}</div>
        {if !empty($rExt.data.slopeUp)}
          <div class="slope slope-up">
            <div class="text">{$rExt.data.slopeUp} m.</div>
            <div class="icon">
              <i class="fas fa-mountain fa-fw"></i>
              <i class="fas fa-long-arrow-alt-up"></i>
            </div>
          </div>
        {/if}
        {if !empty($rExt.data.slopeDown)}
          <div class="slope slope-down">
            <div class="text">{$rExt.data.slopeDown} m.</div>
            <div class="icon">
              <i class="fas fa-mountain fa-fw"></i>
              <i class="fas fa-long-arrow-alt-down"></i>
            </div>
          </div>
        {/if}
      </div>
    {/if}

    {if !empty($rExt.data.difficultyEnvironment) || !empty($rExt.data.difficultyItinerary) ||
        !empty($rExt.data.difficultyDisplacement) || !empty($rExt.data.difficultyEffort) ||
        !empty($rExt.data.difficultyGlobal)}
      <div class="itemRoute routeBar">
        <div class="titleRoutBar">{t}Difficulty{/t}</div>
        {if !empty($rExt.data.difficultyEnvironment)}
          <div class="bar environment difficulty lvlDifficulty_{$rExt.data.difficultyEnvironment}">
            <div class="title">{t}Environment{/t}</div>
            <div class="textDifficulty">{$rExt.data.difficultyEnvironmentText}</div>
            <div class="graph"></div>
            <div class="squares">
              <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEnvironment}"></div>
            </div>
          </div>
        {/if}
        {if !empty($rExt.data.difficultyItinerary)}
          <div class="bar itinerary difficulty lvlDifficulty_{$rExt.data.difficultyItinerary}">
            <div class="title">{t}Itinerary{/t}</div>
            <div class="textDifficulty">{$rExt.data.difficultyItineraryText}</div>
            <div class="graph"></div>
            <div class="squares">
              <div class="barraEsfuerzo ruta_{$rExt.data.difficultyItinerary}"></div>
            </div>
          </div>
        {/if}
        {if !empty($rExt.data.difficultyDisplacement)}
          <div class="bar displacement difficulty lvlDifficulty_{$rExt.data.difficultyDisplacement}">
            <div class="title">{t}Displacement{/t}</div>
            <div class="textDifficulty">{$rExt.data.difficultyDisplacementText}</div>
            <div class="graph"></div>
            <div class="squares">
              <div class="barraEsfuerzo ruta_{$rExt.data.difficultyDisplacement}"></div>
            </div>
          </div>
        {/if}
        {if !empty($rExt.data.difficultyEffort)}
          <div class="bar effort difficulty lvlDifficulty_{$rExt.data.difficultyEffort}">
            <div class="title">{t}Effort{/t}</div>
            <div class="textDifficulty">{$rExt.data.difficultyEffortText}</div>
            <div class="graph"></div>
            <div class="squares">
              <div class="barraEsfuerzo ruta_{$rExt.data.difficultyEffort}"></div>
            </div>
          </div>
        {/if}
        {if !empty($rExt.data.difficultyGlobal)}
          <div class="bar global difficulty lvlDifficulty_{$rExt.data.difficultyGlobal}">
            <div class="title">{t}Global{/t}</div>
            <div class="textDifficulty">{$rExt.data.difficultyGlobalText}</div>
            <div class="graph"></div>
            <div class="squares">
              <div class="barraEsfuerzo ruta_{$rExt.data.difficultyGlobal}"></div>
            </div>
          </div>
        {/if}
      </div>
    {/if}

  </div>
</div>

<style>
  .resourceRouteGraphLegend{
    position: absolute;
    top: 15px;
    right: 30px;
    color: #fff;
    font-weight: bold;
  }

  .dygraph-legend{
    display: none;
    /*box-shadow: 4px 4px 4px #888;*/
  }

  .resourceRouteGraphContainer{
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 22px;
    background-color: rgba(32, 32, 32, 0.7 );
  }

  .resourceRouteGraphContainer .resourceRouteGraph{
    width: 300px;
    height: 80px;
    margin-right: 20px;
    margin-top: 30px;
    margin-bottom: 10px;
  }
</style>


<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtRoutesOptions = {
    resourceId: {$rExt.data.resourceId},
    showGraph: true,
    graphContainer:false
  };
</script>
