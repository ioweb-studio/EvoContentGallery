$j(function(){var a=0;if($j("#images").length){var b=function(b){var c=b.src||"",e=b.title||"",f=b.description||"",g="pointImage_"+a,h="undefined"!=typeof c?c.replace("assets/",window.mb_site_thumb):"",i=$j("<li />",{"class":"item_image"}),j=$j("<div />",{"class":"item_wrapper"}),k=$j("<input />",{"class":"title_input",type:"text",value:e}).val(e),l=$j("<textarea />",{"class":"description_input"}).val(f),m=$j("<input />",{"class":"inputBox image_input",type:"text",value:c,id:g}),n=$j("<input />",{"class":"image_browser_button",type:"button",value:addtext}).attr("data-index","#"+g),o=$j("<a />",{"class":"image_delete",href:"javascript:;"}).html(remtext),p=$j("<div />",{"class":"item_wrapper_image"}).css({display:"none"}),q=$j("<img />",{src:h}),r=$j("<div />",{"class":"input-box"});p.append(q),m.on("change input",d).on("keyup",d),i.append(j.append(r.clone().append("<label>"+titltext+"</label>").append(k)).append(r.clone().append("<label>"+desctext+"</label>").append(l)).append("<label class='imglabel'>"+imglabel+"</label>").append(m).append(n).append(o)).append(p),$j("#sortableImages").append(i),++a},c=function(){$j("#sortableImages").empty(),d()},d=function(){var a=[];$j("#sortableImages .image_input").each(function(){var b=$j("img",$j(this).parent().parent()),c=$j(this).val();title=$j(".title_input",$j(this).parent().parent()).val(),description=$j(".description_input",$j(this).parent().parent()).val(),thumb="undefined"!=typeof c?c.replace("assets/",window.mb_site_thumb).trim():"",b.attr({src:thumb}),$j(this).val(c),a.push({src:c.trim(),title:title.trim(),description:description.trim()}),thumb.length>0?b.show():b.hide()}),a.length?$j("#images_clear").show():$j("#images_clear").hide(),$j("#images").val(JSON.stringify(a))};$j("#sortableImages").on("change input keyup","input[type=text], textarea",d);var e=JSON.parse($j("#images").val().trim());e.length&&$j("#images_clear").show();for(var f=0;f<e.length;++f)b(e[f]);d(),$j(".psimages").on("click",".images_edit > input[type=button]",function(a){a.preventDefault(),$j("#images_clear").show(),b({src:"",title:"",description:""}),d()}).on("click",".images_clear > input[type=button]",function(a){a.preventDefault(),$j("#images_edit").show(),$j(this).parent().hide(),c()}).on("click",".image_browser_button",function(a){var b=$j(".image_input",$j(this).parent());$j("img",$j(this).parent().parent());b.length&&BrowseServer(b.attr("id"))}).on("change input",".image_input",function(a){d()}).on("click",".image_delete",function(a){var b=$j(this).parent().parent();b.remove(),d()}),$j("#images").hide(),$j("#sortableImages").sortable({axis:"y",stop:d,containment:"parent"}).disableSelection()}});