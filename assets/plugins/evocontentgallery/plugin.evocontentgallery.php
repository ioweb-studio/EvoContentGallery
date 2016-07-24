<?php
/**
 * EvoContentGallery
 *
 * EvoContentGallery plugin for MODX Evo
 *
 *
 * @category    plugin
 * @version     0.0.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties
 * @internal    @events OnDocFormRender
 * @internal    @modx_category EvoContentGallery
 * @internal    @legacy_names EvoContentGallery
 * @internal    @installset base, sample
 * @lastupdate  20/07/2016
 * @autor		ProjectSoft (projectsoft@ioweb.ru, projectsoft2009@yandex.ru)
 * 
 */
//AUTHOR: ProjectSoft (projectsoft@ioweb.ru, projectsoft2009@yandex.ru)

if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

if (!class_exists('PluginEvoContentGallery')) {
	require_once MODX_BASE_PATH.'assets/plugins/evocontentgallery/evocontentgallery.class.php';
}

$e =& $modx->event;
switch ($e->name ) {
	case 'OnDocFormPrerender':
		$plg = new PluginEvoContentGallery($modx);
		$out = $plg->run();
		$e->output($out);
		break;
	default:
		return ;
}
?>