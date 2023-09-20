<?php 
require_once("inc/includes.php");
$getPrompts = $prompts->getListFront();
$getPosts = $posts->getListFront();
$getPages = $pages->getListFront();
$getCategories = $categories->getListFront();

$sitemap = "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
$sitemap .= createSitemapEntry($base_url, '', 1.00);
$sitemap .= createSitemapEntry($base_url."/ai-team", '', 0.80);
$sitemap .= createSitemapEntry($base_url."/pricing", '', 0.80);
$sitemap .= createSitemapEntry($base_url."/sign-up", '', 0.80);
$sitemap .= createSitemapEntry($base_url."/sign-in", '', 0.80);
$sitemap .= createSitemapEntry($base_url."/sign-in", '', 0.80);

//Prompts
foreach ($getPrompts as $showPrompts) {
	$sitemap .= createSitemapEntry($base_url."/chat/".$showPrompts->slug, '', 0.80);
}

//Posts
foreach ($getPosts as $showPosts) {
	$sitemap .= createSitemapEntry($base_url."/blog/".$showPosts->slug, '', 0.80);
}

//Pages
foreach ($getPages as $showPages) {
	$sitemap .= createSitemapEntry($base_url."/pages/".$showPages->slug, '', 0.70);
}

//Categories
foreach ($getCategories as $showCategories) {
	$sitemap .= createSitemapEntry($base_url."/ai-team/".$showCategories->slug, '', 0.70);
}
$sitemap .= "</urlset>";
echo $sitemap;