<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach $urlsInfo as $info}
  <url>
    <loc>{$urlPrefix}{$info.loc}</loc>
    <lastmod>{$info.mod}</lastmod>
{if isset($info.changefreq)}
    <changefreq>{$info.changefreq}</changefreq>
{/if}
{if isset($info.priority)}
    <priority>{$info.priority}</priority>
{/if}
  </url>
{/foreach}
</urlset>
