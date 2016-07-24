<?php
class PluginEvoContentGallery {
	// Ссылка на MODx
	public $modx;
	// Таблица галерей
	private $table_galleries;
	// Путь до папки плагина
	private $path;
	// URL до папки плагина
	private $url;
	// Параметры
	private $params;
	// Регистрация содержимого шаблонов (лишний раз не дёргаем файл)
	private $templateRegister = array();
	
	public function __construct($modx){
		$this->modx					= $modx;
		$this->table_galleries		= $this->modx->getFullTableName("evocontent_galleries");
		$this->path					= MODX_BASE_PATH."assets/plugins/evocontentgallery";
		$this->url					= MODX_SITE_URL."assets/plugins/evocontentgallery";
	}
	
	private function getRows() {
		$output	= "";
		$sql	= "SELECT * FROM ".$this->table_galleries." ORDER BY sort ASC";
		$rows 	= $this->modx->db->query($sql);
		while($row=$this->modx->db->getRow($rows)){
			if($row["published"]==1){
				$images	= json_decode($row["images"]);
				$count	= count($images);
				$image	= '';
				if($count){
					$outimg = $this->modx->runSnippet('phpthumb', array(
						'input'		=>	$images[0]->src,
						'options'	=>	'w=70,h=70'
					));
					$image	= '<img class="evocontentgallery_img" src="../'.$outimg.'" />';
				}
				$fields	= array(
					"id"		=>	$row["id"],
					"name"		=>	$row["name"],
					"count"		=>	$count,
					"image"		=>	$image,
					"snippet"	=>	"[[EvoContentGallery? &gid=`".$row["id"]."` &display=`all` &outerTpl=`` &rowTpl=``]]"
				);
				$output .= $this->parseTemplate('li.tpl', $fields);
			}
		}
		return $output;
	}
	
	// Получение шаблона
	private function getFileContents($file){
		$tplFile = $file;
		if (empty($file)) {
    		return "<h1 class=\"error text-center\">Файл шаблона не определён!</h1>";
    	} else {
	    	$file = $this->path.'/tpl/'.$file;
			if(array_key_exists($file, $this->templateRegister)) {
				return $this->templateRegister[$file];
			}
			if(is_file($file)) {
				$content = file_get_contents($file);
				$this->templateRegister[$file] = $content;
				return $content;
			}else {
				return "<h1 class=\"error text-center\">Файл шаблона {$tplFile} не существует!</h1>";
			}
		}
	}
	
	// Парсер шаблона
	private function parseTemplate($tpl, $values = array()) {
		$tpl = $this->getFileContents($tpl);
		foreach ($values as $key => $value) {
    		$tpl = str_replace('[+'.$key.'+]', $value, $tpl); 
    	}
    	$tpl = preg_replace('/(\[\+.*?\+\])/' ,'', $tpl);
    	return $tpl;
	}
	
	private function installTable(){
		$sql 	= "
			CREATE TABLE IF NOT EXISTS ".$this->table_galleries." (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL DEFAULT '',
				`images` mediumtext,
				`content` mediumtext,
				`sort` int(11) NOT NULL DEFAULT '0',
				`published` int(1) NOT NULL DEFAULT '0',
				`createdon` int(20) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
		$this->modx->db->query($sql);
	}
	
	// Запуск
	public function run(){
		$this->installTable();
		$prerender = $this->getRows();
		$fields = array(
			"wrapper"	=>	$prerender,
			"pluginurl"	=>	$this->url,
			"rand"		=>	"?_=".time()
		);
		return $this->parseTemplate('panel.tpl', $fields);
	}
}
?>