{extends file="admin///adminPanel.tpl"}

{block name="content"}

<script type="text/javascript">
  var geozzy = geozzy || {};
  geozzy.rTypeFavouritesAdminData = {$favsData|@json_encode}
</script>
<pre>
{var_dump($favsData)}
</pre>

{if isset($blockContent)}
  {$blockContent}
{/if}

{/block}{*/content*}
