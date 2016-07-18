<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

class EvoContentGallery {
	// Ссылка на MODx
	public $modx;
	// Имя модуля
	public $name;
	// ID модуля
	public 	$moduleid;
	// URL модуля
	public 	$moduleurl;
	// Тема шаблона админки MODx
	public 	$theme;
	// URL к папке модуля
	public 	$moduleTheme;
	// URL админ панели
	public 	$managerUrl;
	// URL папки с иконками темы
	public 	$iconfolder;
	// Заголовок для вывода
	public 	$title;
	// Массив дефолтных значений замены в шаблонах
	private $fields;
	// Регистрация содержимого шаблонов (лишний раз не дёргаем файл)
	private $templateRegister = array();
	// Язык
	private $lang;
	// Сохранёный ID
	private $saveID = 0;
	// Таблица галерей
	private $table_galleries;
	
	public $modulePath = "";
	
	public function __construct($modx, $params=array()) {
		$this->modx = $modx;
		$this->table_galleries = $this->modx->getFullTableName("galleries");
		$this->moduleid 		= (int)$_GET['id'];
		$this->moduleurl		= 'index.php?a=112&id='.$this->moduleid;
		$this->theme			= $this->modx->config['manager_theme'];
		$this->moduleTheme		= MODX_SITE_URL.'assets/modules/evocontentgallery/theme/';
		$this->modulePath		= MODX_BASE_PATH.'assets/modules/evocontentgallery/';
		$this->managerUrl		= MODX_MANAGER_URL;
		$this->iconfolder		= $this->managerUrl.'media/style/'.$this->theme.'/images/icons/';;
		
	}
	
	// Создание таблиц
	public function installModule(){
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
		$sql 		= "SELECT name FROM ".$this->modx->getFullTableName("site_modules")." WHERE id=".$this->moduleid." LIMIT 0,1";
		$rows 		= $this->modx->db->query($sql);
		$this->name	= $this->modx->db->getValue($rows);
		$_lang = $_lang_module = array();
		$managerLanguage = $this->modx->config['manager_language'];
		$userId = $this->modx->getLoginUserID();
		if (!empty($userId)) {
			$lang = $this->modx->db->select('setting_value', $this->modx->getFullTableName('user_settings'), "setting_name='manager_language' AND user='{$userId}'");
			if ($lang = $this->modx->db->getValue($lang)) {
	   	 		$managerLanguage = $lang;
			}
		}
		include MODX_MANAGER_PATH.'includes/lang/english.inc.php';
		if($managerLanguage != 'english') {
			if (file_exists(MODX_MANAGER_PATH.'includes/lang/'.$managerLanguage.'.inc.php')) {
     			include MODX_MANAGER_PATH.'includes/lang/'.$managerLanguage.'.inc.php';
			}
		}
		include $this->modulePath.'lang/english.inc.php';
		if($managerLanguage != 'english') {
			if (file_exists($this->modulePath.'lang/'.$managerLanguage.'.inc.php')) {
     			include $this->modulePath.'lang/'.$managerLanguage.'.inc.php';
			}
		}
		$this->lang = array_merge($_lang_module, $_lang);
		$this->fields = array(
			'manager_url'=>MODX_MANAGER_URL,
			'theme'=>$this->theme,
			'module_theme'=>$this->moduleTheme,
			'module_id'=>$this->moduleid,
			'ioweb'=>'<a class="image_copy" href="http://ioweb.ru/" target="_blank"><img src="'.$this->moduleTheme.'images/ioweb.svg" alt="WEB студия IOWEB" style"vertical-align: middle;" /></a>',
			'module_name'=>$this->lang["module"]." «".$this->name."»",
			'module_debug_info'=>"",
			'rand'=>"?_=".time()
		);
		$this->fields = array_merge($this->fields, $this->lang);
		$this->fields["view_debug"] = print_r($this->fields, true);
	}
	
	// Подготовка строки к вводу в базу
	private function escape($a){
		return $this->modx->db->escape($a);
	}
	
	// Получение шаблона
	private function getFileContents($file){
		$tplFile = $file;
		if (empty($file)) {
    		return "<h1 class=\"error text-center\">Файл шаблона не определён!</h1>";
    	} else {
	    	$file = $this->modulePath.'tpl/'.$file;
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
	
	
}



?>