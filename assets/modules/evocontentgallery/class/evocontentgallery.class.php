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
	// IOWEB
	private $footer = "";
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
		$this->iconfolder		= $this->managerUrl.'media/style/'.$this->theme.'/images/icons/';
		$this->footer			='
		<div class="evocontentgallery evocontentgallery__footer">
			<div class="container">
				<div class="evocontentgallery__pull_right">
					<div class="evocontentgallery__studio evocontentgallery__pull_left">
						<a href="http://ioweb.ru/" target="_blank">
							<img src="'.$this->moduleTheme.'images/ioweb.svg" alt="WEB студия IOWEB" />
							Разработка, поддержка<br />и продвижение сайтов
						</a>
					</div>
					<div class="evocontentgallery__github evocontentgallery__pull_left">
						<a href="https://github.com/ioweb-studio" target="_blank">
							<img src="'.$this->moduleTheme.'images/github.svg" alt="WEB студия IOWEB на GitHub" />
							Профиль<br />на GitHub
						</a>
					</div>
				</div>
			</div>
		</div>
		';
		
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
			'footer'=> $this->footer,
			'module_name'=>$this->lang["module"]." «".$this->name."»",
			'module_debug_info'=>"",
			'rand'=>"?_=".time()
		);
		$this->fields = array_merge($this->fields, $this->lang);
	}
	
	// Сортировка
	private function preSort() {
		$sql 	= "SELECT id FROM ".$this->table_galleries." ORDER BY sort ASC";
		$rows 	= $this->modx->db->query($sql);
		$c = 0;
		if($this->modx->db->getRecordCount($rows) >= 1) {
			$ids = array();
			while($row = $this->modx->db->getRow($rows)) {
				$ids[] = '('.(int)$row['id'] .','.++$c.')';
			}
			$sql = "INSERT INTO ".$this->table_galleries." (id, sort) VALUES ".implode(',', $ids) . " ON DUPLICATE KEY UPDATE sort = VALUES(sort)";
			$this->modx->db->query($sql);
		}
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
	
	// Вывод списка галлерей
	private function showGalleries(){
		$fields = array();
		$sql 	= "SELECT * FROM ".$this->table_galleries." ORDER BY sort ASC";
		$rows = $this->modx->db->query($sql);
		$output = "";
		while($row=$this->modx->db->getRow($rows)){
			$checked 	= (int)$row['published'] == 1 ? " checked=\"checked\"" : "";
			$images = json_decode($row["iamges"]);
			$imagesCount = count($images);
			$output .= "<tr>
				<td class=\"city_name\" align=\"left\">
					<span class=\"name-city\">".$row['id']."</span>
				</td>
				<td class=\"city_name\" align=\"left\">
					<span class=\"name-city\">".$row['name']."</span>
				</td>
				<td class=\"city_name\" align=\"left\">
					<span class=\"name-city\">".$row['sort']."</span>
				</td>
				<td class=\"count-cell text-center\" align=\"center\">
					{$imagesCount}
				</td>
				<td class=\"count-cell text-center\" align=\"center\">
					<input type=\"checkbox\" disabled".$checked." />
				</td>
				<td align=\"right\">
					<ul class=\"actionButtons\">
						<li class=\"\">
							<a class=\"btn primary\" href=\"javascript:;\" onclick=\"document.location.href='index.php?a=112&id=".$this->moduleid."&aid=".$row['id']."&action=1';\"><img src=\"".$this->iconfolder."save.png\" />&nbsp;".$this->lang['module_edit_gallery']."</a>
						</li>
						<li class=\"\">
							<a class=\"btn\" href=\"javascript:;\" onclick=\"document.location.href='index.php?a=112&id=".$this->moduleid."&aid=".$row['id']."&action=3';\"><img src=\"".$this->iconfolder."delete.png\" />&nbsp;".$this->lang['module_delete_gallery']."</a>
						</li>
					</ul>
				</td>
			</tr>";
		}
		$fields['row.galleries'] = $output;
		$fields = array_merge($this->fields, $fields);
		return $this->parseTemplate("galleries.tpl", $fields);
	}
	
	// Добавление / Редактирование Галереи
	private function showGallery($id=0){
		$fields = array(
			'row.id'=>'',
			'row.name' => '',
			'row.images' => '',
			'row.content'=>'',
			'row.sort'=>'',
			'row.published'=>'',
			'row.createdon'=>''
		);
		// Редактор
		$use_editor = intval($this->modx->config["use_editor"]);
		$which_editor = $this->modx->config["which_editor"];
		$stay = (empty($_REQUEST["stay"]) || !is_numeric($_REQUEST["stay"]) || !isset($_REQUEST["stay"])) ? 0 : intval($_REQUEST["stay"]);
		$stayOpt=array($this->lang['edit_close'], $this->lang['edit_continue']);
		$stayOptions = "";
		
		for($i=0; $i < count($stayOpt); ++$i){
			$selected = $stay==$i ? " selected=\"selected\"" : "";
			$stayOptions .= "<option value=\"".$i."\"".$selected.">".$stayOpt[$i]."</option>";
		}
		$fields['option.stay'] = $stayOptions;
		if(!$id){
			// Новая Галерея
			$fields['module_show_title'] = $this->lang["module_add_gallery"];
			$fields['row.id'] = "<input type=\"hidden\" name=\"action\" value=\"2\" />";
			$fields['row.name'] = "<input class=\"inputBox\" type=\"text\" name=\"name\" value=\"\" maxlength=\"255\" />";
			$fields['row.published'] = "<label class=\"warning\">".$this->lang['module_check_published']."&nbsp;<input type=\"checkbox\" class=\"checkbox\" name=\"publishedcheck\" checked=\"checked\" onclick=\"changestate(document.module.published);\" /><input type=\"hidden\" name=\"published\" value=\"1\" /></label>";
			$fields['row.content'] = "<textarea name=\"gallery_content\" id=\"gallery_content\" onchange=\"documentDirty=true;\"></textarea>";
			$fields['row.images'] = "&#x005B;&#x005D;";
		}else{
			// Редактирование
			$result = $this->modx->db->select( '*', $this->table_galleries, "id = $id", 'id ASC', '1');
			if($this->modx->db->getRecordCount( $result ) == 1){
				$row = $this->modx->db->getRow($result);
				$fields['module_show_title'] = $this->lang["module_gallery"];
				$fields['row.name'] = "<input class=\"inputBox\" type=\"text\" name=\"name\" value=\"".$this->modx->htmlspecialchars(stripslashes($row['name']))."\" maxlength=\"255\" />";
				$fields['row.id'] = "<input type=\"hidden\" name=\"pid\" value=\"".$row['id']."\" /><input type=\"hidden\" name=\"action\" value=\"2\" />";
				$checked = $row["published"]==0 ? " " : " checked=\"checked\" ";
				$chekval = $row["published"]==0 ? 0 : 1;
				$fields['row.published'] = "<label class=\"warning label_maps\">".$this->lang['module_check_published']."&nbsp;<input type=\"checkbox\" class=\"checkbox\" name=\"publishedcheck\"".$checked."onclick=\"changestate(document.module.published);\" /><input type=\"hidden\" name=\"published\" value=\"".$chekval."\" /></label>";
				$fields['row.content'] = "<textarea name=\"gallery_content\" id=\"gallery_content\" onchange=\"documentDirty=true;\">".$this->modx->htmlspecialchars(stripslashes($row['content']))."</textarea>";
				$fields['row.images'] = $this->modx->htmlspecialchars(stripslashes($row['images']));
			}else{
				// Записи нет
				// Отправить в список галлерей
				return $this->showGalleries();
			}
		}
		$richtexteditor = array(
			"gallery_content"
		);
		$fields["richtexteditor"] = "";
		
		$evtOut = $this->modx->invokeEvent('OnModFormRender', array('id' => $id));
		if(is_array($evtOut)) $fields["richtexteditor"] .= implode('',$evtOut);
		if ($use_editor == 1) {
			$evtOut = $this->modx->invokeEvent('OnRichTextEditorInit', array(
				'editor' => $which_editor,
				'elements' => $richtexteditor
			));
			if (is_array($evtOut))
				$fields["richtexteditor"] .=  implode('', $evtOut);
		}
		$this->fields['mb_site_thumb'] = MODX_SITE_URL.'assets/'.$this->modx->config["thumbsDir"]."/";
		$this->fields['debug_info'] = $this->fields['mb_site_thumb'];
		$fields = array_merge($this->fields, $fields);
		return $this->parseTemplate("gallery.tpl", $fields);
	}
	
	// Сохранение Галереи
	private function saveGallery($id=0){
		$cityname = (empty($_REQUEST['name']) || trim($_REQUEST['name'])=="") ? "Новый Объект" : trim($_REQUEST['name']);
		$this->saveID = $id;
		if(!$id){
			// Add gallery
			$result = $this->modx->db->select('id', $this->table_galleries);
			$total = $this->modx->db->getRecordCount($result)+1;
			$fields = array(
				'name' => $this->escape($cityname),
				'images' => $this->escape(trim($_REQUEST['images'])),
				'content'=>$this->escape(trim($_REQUEST['gallery_content'])),
				'sort'=>$total,
				'published'=> $this->escape(trim($_REQUEST['published'])),
				'createdon'=>time() + $this->modx->config['server_offset_time']
			);
			$id = $this->modx->db->insert($fields, $this->table_galleries);
		}else{
			$fields = array(
				'name' => $this->escape($cityname),
				'images' => $this->escape(trim($_REQUEST['images'])),
				'content'=>$this->escape(trim($_REQUEST['gallery_content'])),
				'published'=> $this->escape(trim($_REQUEST['published']))
			);
			$this->modx->db->update($fields, $this->table_galleries, "id = ".$this->saveID);
			$this->preSort();
		}
	}
	
	// Удаление галлереи
	private function deleteGallery($id){
		$this->modx->db->delete($this->table_galleries, "id = $id");
	}
	
	// Определение действия
	private function mackeAction(){
		
		$this->fields['module_debug_info'] = "";
		
		if(empty($_REQUEST['action']) || !intval($_REQUEST['action'])){
			$this->fields['module_debug_info'] .= "!intval empty action<br />";
			return $this->showGalleries();
		}
		$action = intval($_REQUEST['action']);
		switch($action){
			/**
			** Manipulate Galleries
			**/
			case 1: // Add Gallery
				$id = (empty($_REQUEST["aid"]) || !is_numeric($_REQUEST["aid"]) || !isset($_REQUEST["aid"])) ? 0 : intval($_REQUEST["aid"]);
				return $this->showGallery($id);
				break;
			case 2: // Save Gallery
				$id = (empty($_REQUEST["pid"]) || !is_numeric($_REQUEST["pid"])) ? 0 : intval($_REQUEST["pid"]);
				$stay = (empty($_REQUEST["stay"]) || !is_numeric($_REQUEST["stay"]) || !isset($_REQUEST["stay"])) ? 0 : intval($_REQUEST["stay"]);
				$this->saveGallery($id, $parent);
				if($stay){
					return $this->showGallery($this->saveID);
				}
				return $this->showGalleries();
				break;
			case 3: // Delete Gallery
				$id = (empty($_REQUEST["aid"]) || !is_numeric($_REQUEST["aid"])) ? 0 : intval($_REQUEST["aid"]);
				if($id){
					$this->deleteGallery($id);
					$this->preSort();
				}
				return $this->showGalleries();
				break;
			default:
				return $this->showGalleries();
				break;
		}
	}
	
	// Запуск модуля
	public function run() {
		$this->installModule();
		return $this->mackeAction();
	}
}



?>