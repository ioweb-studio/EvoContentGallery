<!DOCTYPE html><html lang="ru"><head><link rel="stylesheet" type="text/css" href="media/style/[+theme+]/style.css"><link rel="stylesheet" type="text/css" href="[+module_theme+]css/main.css[+rand+]"><script src="[+module_theme+]js/jquery.js"></script><script type="text/javascript" src="media/script/mootools/mootools.js"></script><script type="text/javascript" src="media/script/mootools/moodx.js"></script><script type="text/javascript" src="media/script/tabpane.js"></script><script type="text/javascript" src="media/script/datefunctions.js"></script><script type="text/javascript" src="media/calendar/datepicker.js"></script></head><body><div id="actions"><ul class="actionButtons"><li id="Button2"><a href="javascript:;" class="primary" onclick="document.location.href='index.php?a=112&id=[+module_id+]&action=1';"><img src="media/style/[+theme+]/images/icons/add.png">&nbsp;[+module_add_cite+]</a></li><li id="Button1"><a href="javascript:;" onclick="closeModule();"><img src="media/style/[+theme+]/images/icons/stop.png">&nbsp;[+module_close+]</a></li></ul></div><div class="sectionBody"><div class="tab-pane" id="modulePane"><script type="text/javascript">window.tp = new WebFXTabPane( document.getElementById( "modulePane"), true);</script><div class="tab-page" id="tabModule_0"><h2 class="tab">[+module_cites_list+]</h2><script type="text/javascript">window.tp.addTabPage( document.getElementById( "tabModule_0" ) );</script><table border="0" cellspacing="0" cellpadding="1" class="sortabletable"><tr><th>[+module_cite+]</th><th>[+module_check_published+]</th><th>[+module_action+]</th></tr><tbody id="sortableTable">[+row.cites+]</tbody></table><div></div></div></div></div>[+footer+]</body></html>