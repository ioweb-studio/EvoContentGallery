$j(function(){var a=0;if($j("#images").length){var b=function(b){var e=b.src||"",g=b.title||"",h=b.description||"",i="pointImage_"+a,j="undefined"!=typeof e?e.replace("assets/",window.mb_site_thumb):"",k=$j("<li />",{"class":"item_image"}),l=$j("<div />",{"class":"item_wrapper"}),m=$j("<input />",{"class":"title_input",type:"text",value:g}).val(g),n=$j("<textarea />",{"class":"description_input"}).val(h),o=$j("<input />",{"class":"inputBox image_input",type:"text",value:e,id:i}),p=$j("<input />",{"class":"image_browser_button",type:"button",value:addtext}).attr("data-index","#"+i),q=$j("<a />",{"class":"image_delete",href:"javascript:;"}).html(remtext),r=$j("<div />",{"class":"item_wrapper_image"}).css({display:"none"}),s=$j("<img />",{src:j}).on("load",d).on("error",c),t=$j("<div />",{"class":"input-box"});r.append(s),o.on("change",f).on("keyup",f),k.append(l.append(t.clone().append("<label>"+titltext+"</label>").append(m)).append(t.clone().append("<label>"+desctext+"</label>").append(n)).append("<label class='imglabel'>"+imglabel+"</label>").append(o).append(p).append(q)).append(r),$j("#sortableImages").append(k),++a},c=function(a){a.preventDefault(),$j(this).parent().hide()},d=function(a){a.preventDefault(),$j(this).parent().show()},e=function(){$j("#sortableImages").empty(),f()},f=function(){var a=[];$j("#sortableImages .image_input").each(function(){var b=$j("img",$j(this).parent().parent()),c=$j(this).val();title=$j(".title_input",$j(this).parent().parent()).val(),description=$j(".description_input",$j(this).parent().parent()).val(),thumb="undefined"!=typeof c?c.replace("assets/",window.mb_site_thumb).trim():"",b.attr({src:thumb}),console.log(thumb,$j(this).parent().parent()),$j(this).val(c),a.push({src:c.trim(),title:title.trim(),description:description.trim()})}),a.length?$j("#images_clear").show():$j("#images_clear").hide(),$j("#images").val(JSON.stringify(a))},g=JSON.parse($j("#images").val().trim());g.length&&$j("#images_clear").show();for(var h=0;h<g.length;++h)b(g[h]);f(),$j(".psimages").on("click",".images_edit > input[type=button]",function(a){a.preventDefault(),$j("#images_clear").show(),b({src:"",title:"",description:""}),f()}).on("click",".images_clear > input[type=button]",function(a){a.preventDefault(),$j("#images_edit").show(),$j(this).parent().hide(),e()}).on("click",".image_browser_button",function(a){var b=$j(".image_input",$j(this).parent());$j("img",$j(this).parent().parent());b.length&&BrowseServer(b.attr("id"))}).on("change input",".image_input",function(a){f()}).on("click",".image_delete",function(a){var b=$j(this).parent().parent();b.remove(),f()}),$j("#images").hide(),$j("#sortableImages").sortable({axis:"y",stop:f,containment:"parent"}).disableSelection()}});