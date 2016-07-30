<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}
global $modx;

$table_galleries = $modx->getFullTableName("evocontent_galleries");

$gid = (isset($gid) && (int)$gid>0) ? (int)$gid : 0;
$rowTpl = (isset($rowTpl)) ? $modx->getTpl($rowTpl) : "";
$outerTpl = (isset($outerTpl)) ? $modx->getTpl($outerTpl) : "";
$display = (isset($display) && (int)$display>0) ? $display : "all";
$output = "";

// Плейсхолдеры outerTpl
$fieldsOuter = array(
	"id"		=>	"",
	"title"		=>	"",
	"content"	=>	"",
	"wrapper"	=>	""
);

$sql = $modx->db->select( '*', $table_galleries, "id = $gid", 'id ASC', '1');

if($modx->db->getRecordCount($sql) == 1){
	$row = $modx->db->getRow($sql);
	$fieldsOuter["id"] = $row["id"];
	$fieldsOuter["title"] = $row["name"];
	$fieldsOuter["content"] = $row["content"];
	$images = json_decode($row["images"]);
	$rowsOuter = "";
	$display = $display=="all" ? count($images) : $display;
	if(count($images)){
		for($i = 0; $i < count($images); ++$i){
			if($i < $display){
				
				// Плейсхолдеры rowTpl
				$fields = array(
					"num"			=>	$i+1,
					"title"			=>	$images[$i]->title,
					"description"	=>	$images[$i]->description,
					"image"			=>	$images[$i]->src
				);
				$rowsOuter .= $modx->parseText($rowTpl, $fields, '[+', '+]');
			}else{
				break;
			}
		}
		$fieldsOuter["wrapper"] = $rowsOuter;
	}
	$output .= $modx->parseText($outerTpl, $fieldsOuter, '[+', '+]');
}

return $output;
?>