<!DOCTYPE html><html lang="ru"><head><link rel="stylesheet" type="text/css" href="media/style/[+theme+]/style.css"><link rel="stylesheet" type="text/css" href="[+module_theme+]css/main.css[+rand+]"><script src="[+module_theme+]js/jquery.js"></script><script>$j = jQuery.noConflict();</script><script type="text/javascript" src="media/script/mootools/mootools.js"></script><script type="text/javascript" src="media/script/mootools/moodx.js"></script><script type="text/javascript" src="media/script/tabpane.js"></script><script type="text/javascript" src="media/script/datefunctions.js"></script><script type="text/javascript" src="media/calendar/datepicker.js"></script></head><body><h1 class="evocontentgallery__modulename">[+ioweb+] [+module_evocontentgallery_name+]</h1><form name="module" id="module" class="module" method="post" action="index.php?a=112&id=[+module_id+]" enctype="multipart/form-data">[+row.id+]<div id="actions"><ul class="actionButtons"><li><select id="stay" name="stay">[+option.stay+]</select></li><li id="Button1"><a href="javascript:;" class="primary" onclick="documentDirty=false; document.module.save.click();"><img src="media/style/[+theme+]/images/icons/save.png">&nbsp;[+module_save+]</a></li><li id="Button2"><a href="javascript:;" onclick="documentDirty=false; document.location.href='index.php?a=112&id=[+module_id+]&action=9&parent=[+module_parent+]';"><img src="media/style/[+theme+]/images/icons/stop.png">&nbsp;[+module_cancal+]</a></li></ul></div><div class="sectionBody"><div class="tab-pane" id="modulePane"><script type="text/javascript">window.tp = new WebFXTabPane( document.getElementById( "modulePane"), true);</script><div class="tab-page" id="tabModule_0"><h2 class="tab">[+module_show_title+]&nbsp;</h2><script type="text/javascript">window.tp.addTabPage( document.getElementById( "tabModule_0" ) );</script><table border="0" cellspacing="0" cellpadding="1" width="99%"><tr style="height: 24px"><td width="100"><span class="warning">[+module_gallery_name+]</span></td><td>[+row.name+]&nbsp;[+row.published+]</td></tr><tr><td><span class="warning">[+module_text_content+]</span></td><td>[+row.content+]</td></tr><tr><td><span class="warning">[+module_point_gallery+]</span></td><td><div class="psimages"><textarea style="width:100%" onchange="documentDirty=true;" name="images" id="images" cols="" rows="">[+row.images+]</textarea><ul id="sortableImages" class="list sortable"></ul><div class="clearfix"></div><div id="images_edit" class="editedBtn images_edit"><input type="button" class="primary" value="Добавить Изображение">.</div><div id="images_clear" class="editedBtn images_clear" style="display: none"><input type="button" class="primary" value="Удалить все Изображения"></div><div class="clearfix"></div></div></td></tr></table></div></div></div><div>[+richtexteditor+]</div><input type="submit" name="save" value="[+module_save+]" style="display:none"></form>[+debug_info+] [+footer+]<script type="text/javascript">window.mb_site_thumb = "[+mb_site_thumb+]";
			var addtext = "Вставить",
				remtext = "Удалить",
				titltext = "Название:&nbsp;",
				desctext = "Описание:&nbsp;",
				imglabel = "Изображение:&nbsp;",
				lastImageCtrl,
				lastFileCtrl;
			function changestate(element) {
				var currval = eval(element).value;
				if (currval==1) {
					eval(element).value=0;
				} else {
					eval(element).value=1;
				}
				documentDirty=true;
			};
			window.OpenServerBrowser = function(url, width, height ) {
					var iLeft = (screen.width  - width) / 2 ;
					var iTop  = (screen.height - height) / 2 ;

					var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes' ;
					sOptions += ',width=' + width ;
					sOptions += ',height=' + height ;
					sOptions += ',left=' + iLeft ;
					sOptions += ',top=' + iTop ;

					var oWindow = window.open( url, 'FCKBrowseWindow', sOptions ) ;
				};			
			window.BrowseServer = function(ctrl) {
					lastImageCtrl = ctrl;
					var w = screen.width * 0.5;
					var h = screen.height * 0.5;
					OpenServerBrowser('[+manager_url+]media/browser/mcpuk/browser.php?Type=images', w, h);
				};
			window.BrowseFileServer = function(ctrl) {
					lastFileCtrl = ctrl;
					var w = screen.width * 0.5;
					var h = screen.height * 0.5;
					OpenServerBrowser('[+manager_url+]manager/media/browser/mcpuk/browser.php?Type=files', w, h);
				};
			window.SetUrlChange = function(el) {
					if ('createEvent' in document) {
						var evt = document.createEvent('HTMLEvents');
						evt.initEvent('change', false, true);
						el.dispatchEvent(evt);
					} else {
						el.fireEvent('onchange');
					}
				};
			window.SetUrl = function(url, width, height, alt) {
					if(lastFileCtrl) {
						var c = document.getElementById(lastFileCtrl);
						if(c && c.value != url) {
							c.value = url;
							SetUrlChange(c);
						}
						lastFileCtrl = '';
					} else if(lastImageCtrl) {
						var c = document.getElementById(lastImageCtrl);
						if(c && c.value != url) {
							c.value = url;
							SetUrlChange(c);
						}
						lastImageCtrl = '';
					} else {
						return;
					}
				};</script><script type="text/javascript" src="[+module_theme+]js/main.js[+rand+]"></script></body></html>