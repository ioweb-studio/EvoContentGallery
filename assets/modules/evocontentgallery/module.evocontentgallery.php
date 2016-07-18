<?php
/**
 * EvoContentGallery
 *
 * EvoContentGallery module for MODX Evo
 *
 * @category	module
 * @version 	0.0.1
 * @internal	@properties
 * @internal	@guid evocontentgallery10dbbce2a1615	
 * @internal	@shareparams 1
 * @internal	@dependencies requires files located at /assets/modules/evocontentgallery/
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 * @lastupdate  18/07/2016
 */

//AUTHOR: ProjectSoft (projectsoft@ioweb.ru, projectsoft2009@yandex.ru)
if (IN_MANAGER_MODE != 'true') {
	die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');
}
include_once(MODX_BASE_PATH.'assets/modules/evocontentgallery/class/evocontentgallery.class.php');
$OP = new EvoContentGallery($modx);
$output = $OP->run();
echo $output;
?>