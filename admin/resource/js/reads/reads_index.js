var object_module_edit;var article_save_function;var limit_count;var image_count=1;$(document).ready(function(){var e={};$("[nctype='btn_module_title_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.html();$("#dialog_module_title_edit").feiwa_show_dialog({width:640,title:"编辑标题"});$("#input_module_title").val(e)});$("#btn_module_title_save").click(function(){object_module_edit.html($("#input_module_title").val());$("#dialog_module_title_edit").hide()});$(".article-tag-selected-list").sortable();$("#btn_module_tag_edit").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();e.find("li").append('<i nctype="btn_module_tag_select_drop" class="reads-index-tag-select-drop" class="删除所选"></i>');$("#dialog_module_tag_edit").feiwa_show_dialog({width:640,title:"编辑标签"});$("#article_tag_selected_list").html(e.html())});$(".article-tag-list [nctype='btn_tag_select']").live("click",function(){var e=$(this).attr("data-tag-id");$("#article_tag_selected_list li").each(function(){if($(this).attr("data-tag-id")==e){e=0}});if(e>0){$("#article_tag_selected_list").append($(this).clone())}return false});$(".article-tag-selected-list [nctype='btn_tag_select']").live("click",function(){$(this).remove();return false});$("#btn_module_tag_save").click(function(){var e=$("#article_tag_selected_list").clone();e.find("[nctype='btn_module_tag_select_drop']").remove();object_module_edit.html(e.html());$("#dialog_module_tag_edit").hide()});$("[nctype='btn_module_image_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");if(parseInt($(this).attr("image_count"),10)!==1){$("#btn_image_upload").attr("multiple","1");image_count=2}var e=object_module_edit.clone();if(e.find("li.picture").length>0){e.find("li").each(function(){var e=$(this).find("img");var t=e.parent().attr("href");var i=e.attr("image_name");$(this).append('<a nctype="btn_module_drop_image" image_name="'+i+'" class="handle-del" title="删除该图片"><i></i>&nbsp;</a>');$(this).append('<div class="pic-url">相关网址：<input type="text" class="w200" value="'+t+'"> 网址应包含http://</div>')})}$("#module_image_edit_explain").html("<i class='fa fa-lightbulb-o'></i>"+$(this).attr("data-title"));$("#dialog_module_image_edit").feiwa_show_dialog({width:640,title:"编辑图片"});if(e.find("li.picture").length>0){$("#image_selected_list").html(e.html())}else{$("#image_selected_list").html("")}});$("#btn_image_upload").fileupload({dataType:"json",url:"index.php?app=reads_index&feiwa=image_upload",add:function(e,t){t.submit()},done:function(e,t){result=t.result;if(result.status=="success"){var i='<li class="picture">';i+='<div class="reads-thumb"><a href="" target="_blank"><img data-image-name="'+result.file_name+'" src="'+result.file_url+'" alt="" class="t-img" /></a></div>';i+='<a nctype="btn_module_drop_image" image_name="'+result.file_name+'" class="handle-del" title="删除该图片"><i></i>&nbsp;</a>';i+='<div class="pic-url">';i+="相关网址：";i+='<input type="text" value="" class="w200"/>';i+=" 网址应包含http://</div></li>";if(image_count===1){$("#image_selected_list li").each(function(){$("#add_form").append('<input name="module_drop_image[]" type="hidden" value="'+$(this).find("img").attr("data-image-name")+'" />')});$("#image_selected_list").html("")}$("#image_selected_list").append(i)}else{showError(result.error)}}});$("[nctype='btn_module_drop_image']").live("click",function(){$("#add_form").append('<input name="module_drop_image[]" type="hidden" value="'+$(this).attr("image_name")+'" />');$(this).parents("li.picture").remove()});$("#btn_module_image_save").click(function(){var e=$("#image_selected_list").clone();e.find("li").each(function(){$(this).find("img").parent().attr("href",$(this).find("input").val())});e.find(".handle-del").remove();e.find(".pic-url").remove();object_module_edit.html(e.html());$("#dialog_module_image_edit").hide()});$("#article_selected_list").sortable();$("[nctype='btn_module_article_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();article_save_function=$(this).attr("save_function");limit_count=parseInt($(this).attr("limit_count"),10);if(!limit_count){limit_count=0}e.find('[nctype="article_view"]').remove();e.find("li").each(function(){var e=$(this).find("span.title");var t=$(this).find("span.title a");$(this).prepend('<span class="article-image" nctype="reads_index_not_display"><p><img src="'+t.attr("article_image")+'"></p></span>');e.prepend('<em class="class-name" nctype="reads_index_not_display">['+t.attr("class_name")+"]</em>");e.append('<em class="publish-time" nctype="reads_index_not_display">('+t.attr("article_publish_time")+")</em>");$(this).append('<span title="'+t.attr("article_abstract")+'" class="article-abstract" nctype="reads_index_not_display">文章摘要：'+t.attr("article_abstract")+"</span>");$(this).append('<a nctype="btn_article_select" href="JavaScript:void(0);" class="delete" title="选择删除"></a>')});$("#dialog_module_article_edit").feiwa_show_dialog({width:640,title:"编辑文章"});$("#article_selected_list").html(e.html());$("#article_search_list").html("")});$("#btn_article_search").click(function(){var e=$("[name='article_search_type']:checked").val();var t=$("#input_article_search_keyword").val();if(t!==""){$("#div_article_select_list").load("index.php?app=reads_index&feiwa=get_article_list&"+$.param({search_type:e,search_keyword:t}))}});$("#div_article_select_list .demo").live("click",function(e){$("#div_article_select_list").load($(this).attr("href"));return false});$("#article_search_list [nctype='btn_article_select']").live("click",function(){var e=$("#article_selected_list li").length;if(e<limit_count||limit_count===0){var t=$(this).parent().clone();t.find("[nctype='btn_article_select']").attr("title","删除");$("#article_selected_list").append($("<div />").append(t).html())}});$("#article_selected_list [nctype='btn_article_select']").live("click",function(){$(this).parent().remove()});$("#btn_module_article_save").click(function(){$("#article_selected_list").find("[nctype='btn_article_select']").remove();$("#article_selected_list").find("[nctype='reads_index_not_display']").remove();object_module_edit.html("");e[article_save_function]();$("#dialog_module_article_edit").hide()});e["article_type_0_save"]=function(){var e=1;$("#article_selected_list li").each(function(){if(e>1){$(this).attr("class","reads-index-article-normal")}else{$(this).attr("class","reads-index-article-focus")}object_module_edit.append($(this));e++})};e["article_type_1_save"]=function(){var e=1;$("#article_selected_list li").each(function(){$(this).prepend('<span nctype="article_view" class="reads-index-count_'+e+'">'+e+"</span>");$(this).append('<span nctype="article_view" class="reads-index-click-count">'+$(this).find('[nctype="article_item"]').attr("article_click")+"人关注</span>");object_module_edit.append($(this));e++})};e["article_type_2_save"]=function(){var e=1;$("#article_selected_list li").each(function(){var t=$(this).find('[nctype="article_item"]');if(e>1){$(this).attr("class","reads-index-article-normal");$(this).prepend('<span nctype="article_view" class="reads-index-article-class">'+t.attr("class_name")+"</span>");$(this).append('<span nctype="article_view" class="reads-index-article-date">'+t.attr("article_publish_time").substring(8)+"日</span>")}else{$(this).attr("class","reads-index-article-focus")}object_module_edit.append($(this));e++})};e["article_type_3_save"]=function(){$("#article_selected_list li").each(function(){var e=$(this).find('[nctype="article_item"]');$(this).prepend('<div class="reads-thumb" nctype="article_view"><a nctype="article_view" href="'+e.attr("href")+'"><img class="t-img" src="'+e.attr("article_image")+'" /></a></div');object_module_edit.append($(this))})};e["article_type_4_save"]=function(){var e=1;$("#article_selected_list li").each(function(){$(this).append('<span nctype="article_view" class="reads-index-article-abstract">'+$(this).find('[nctype="article_item"]').attr("article_abstract")+"</span>");object_module_edit.append($(this));e++})};e["article_type_5_save"]=function(){$("#article_selected_list li").each(function(){var e=$(this).find('[nctype="article_item"]');$(this).attr("class","reads-index-article-normal");$(this).prepend('<span nctype="article_view" class="reads-index-article-class">'+e.attr("class_name")+"</span>");$(this).append('<span nctype="article_view" class="reads-index-article-date">'+e.attr("article_publish_time").substring(8)+"日</span>");object_module_edit.append($(this))})};e["article_type_6_save"]=function(){$("#article_selected_list li").each(function(){$(this).attr("class","reads-index-article-normal");object_module_edit.append($(this))})};$("#goods_selected_list").sortable();$("[nctype='btn_module_goods_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();e.find("dl").append('<dd class="taobao-item-delete" nctype="btn_goods_delete" title="删除添加的商品">&nbsp;</dd>');limit_count=parseInt($(this).attr("limit_count"),10);if(!limit_count){limit_count=0}$("#dialog_module_goods_edit").feiwa_show_dialog({width:640,title:"编辑商品"});$("#goods_selected_list").html(e.html())});$("#btn_goods_search").live("click",function(){var e=$("#goods_selected_list");var t=$("#input_goods_link");var i=t.val();t.val("");if($("#goods_selected_list li").length<limit_count||limit_count===0){if(i!=""){var l=encodeURIComponent(i);$.getJSON("index.php?app=reads_index&feiwa=goods_info_by_url",{url:l},function(t){if(t.result=="true"){var i='<li nctype="btn_goods_select"><dl>';i+='<dt class="name"><a href="'+t.url+'" target="_blank">'+t.title+"</a></dt>";i+='<dd class="reads-thumb" title="'+t.title+'"><a href="'+t.url+'" target="_blank"><img src="'+t.image+'" class="t-img"/></a></dd>';i+='<dd class="price"><em>'+t.price+"</em></dd>";i+='<dd class="taobao-item-delete" nctype="btn_goods_delete" title="删除添加的商品">&nbsp;</dd>';i+="</dl></li>";$(e).append(i)}else{alert(t.message)}})}}});$("[nctype='btn_goods_delete']").live("click",function(){$(this).parent().parent().remove()});$("#btn_module_goods_save").live("click",function(){var e=$("#goods_selected_list").clone();e.find("[nctype='btn_goods_delete']").remove();object_module_edit.html(e.html());$("#dialog_module_goods_edit").hide()});$("#brand_selected_list").sortable();$("[nctype='btn_module_brand_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();e.find("li").append('<div nctype="btn_brand_select" class="add-brand"><i></i></div>');limit_count=parseInt($(this).attr("limit_count"),10);if(!limit_count){limit_count=0}if($("#div_brand_select_list").html()===""){$("#div_brand_select_list").load("index.php?app=reads_index&feiwa=get_brand_list")}$("#dialog_module_brand_edit").feiwa_show_dialog({width:640,title:"编辑品牌"});$("#brand_selected_list").html(e.html())});$("#div_brand_select_list .demo").live("click",function(e){$("#div_brand_select_list").load($(this).attr("href"));return false});$("#brand_search_list [nctype='btn_brand_select']").live("click",function(){var e=$("#brand_selected_list li").length;if(e<limit_count||limit_count===0){var t=$(this).parent().clone();$("#brand_selected_list").append($("<div />").append(t).html())}});$("#brand_selected_list [nctype='btn_brand_select']").live("click",function(){$(this).parent().remove()});$("#btn_module_brand_save").click(function(){var e=$("#brand_selected_list").clone();e.find("[nctype='btn_brand_select']").remove();object_module_edit.html(e.html());$("#dialog_module_brand_edit").hide()});$("#goods_class_selected_list").sortable();$("[nctype='btn_module_goods_class_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();e.find("dt").prepend("<i>删除</i>");e.find("dd").prepend("<i>删除</i>");if($("#select_goods_class_list option").length<1){$("#select_goods_class_list").html("");$("#select_goods_class_list").append('<option value="0">请选择</option>');$.getJSON("index.php?app=reads_index&feiwa=get_goods_class_list_json",{},function(e){var t=e.length;for(var i=0;i<t;i++){$("#select_goods_class_list").append('<option value="'+e[i]["gc_id"]+'">'+e[i]["gc_name"]+"</option>")}})}$("#dialog_module_goods_class_edit").feiwa_show_dialog({width:640,title:"编辑分类"});if(e.find("dl").length>0){$("#goods_class_selected_list").html(e.html())}});$("#select_goods_class_list").change(function(){$.get("index.php?app=reads_index&feiwa=get_goods_class_detail",{class_id:$(this).val()},function(e){$("#goods_class_selected_list").append(e)})});$("#goods_class_selected_list dt i").live("click",function(){$(this).parents("dl").remove()});$("#goods_class_selected_list dd i").live("click",function(){$(this).parents("dd").remove()});$("#btn_module_goods_class_save").click(function(){var e=$("#goods_class_selected_list").clone();e.find("i").remove();object_module_edit.html(e.html());$("#dialog_module_goods_class_edit").hide()});$("#btn_module_micro_edit").live("click",function(){var e=SHARESHOW_SITE_URL;var t="";var i="";var l="";var a="";var c="";var d="";var _=0;t=e+"/index.php?app=api&feiwa=get_micro_name&callback=?";$.getJSON(t,{data_type:"html"},function(e){l=e;n()});t=e+"/index.php?app=api&feiwa=get_personal_class&callback=?";$.getJSON(t,{data_type:"html"},function(e){a=e;n()});t=e+"/index.php?app=api&feiwa=get_personal_commend&callback=?";$.getJSON(t,{data_type:"html",data_count:8},function(e){c=e;n()});t=e+"/index.php?app=api&feiwa=get_store_commend&callback=?";$.getJSON(t,{data_type:"html",data_count:10},function(e){d=e;n()});function n(){_++;if(_>3){i+='<div class="reads-module-micro-left">';i+='<div class="title-bar">';i+='<div class="micro-api-title">'+l+"</div>";i+='<div class="micro-api-personal-class">'+a+"</div>";i+='<div class="title-more"><a href='+e+' class="more" target="_blank">更多</a></div>';i+="</div>";i+='<div class="micro-api-personal-list">'+c+"</div>";i+="</div>";i+='<div class="micro-api-store-list">'+d+"</div>";i+='<div class="clear"></div>';$("#micro_content").html(i)}}});$("#btn_module_circle_edit").live("click",function(){var e=$(this).parent().parent().find("[nctype='object_module_edit']");var t=CIRCLE_SITE_URL;var i="";var l="";var a="";var c="";var d=0;i=t+"/index.php?app=api&feiwa=get_theme_list&callback=?";$.getJSON(i,{data_type:"html"},function(e){l=e;_()});i=t+"/index.php?app=api&feiwa=get_reply_themelist&callback=?";$.getJSON(i,{data_type:"html"},function(e){a=e;_()});i=t+"/index.php?app=api&feiwa=get_more_membertheme&callback=?";$.getJSON(i,{data_type:"html",data_count:4},function(e){c=e;_()});function _(){d++;if(d>2){e.html(l+a+c)}}});$("#store_selected_list").sortable();$("[nctype='btn_module_store_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();e.find("li").each(function(){$(this).find("dl").append('<dd nctype="btn_store_select" class="handle-button" title="删除"></dd>')});$("#dialog_module_store_edit").feiwa_show_dialog({width:640,title:"编辑店铺"});$("#store_selected_list").html(e.html());$("#store_search_list").html("")});$("#btn_store_search").click(function(){var e=$("#input_store_search_keyword").val();if(e!==""){$("#div_store_select_list").load("index.php?app=reads_index&feiwa=get_store_list&"+$.param({search_keyword:e}))}});$("#div_store_select_list").on("click",".demo",function(){$("#div_store_select_list").load($(this).attr("href"));return false});$("#div_store_select_list").on("click",'[nctype="btn_store_select"]',function(){var e=$("#article_selected_list li").length;var t=$(this).parents("li").clone();t.find("[nctype='btn_store_select']").attr("title","删除");$("#store_selected_list").append($("<div />").append(t).html())});$("#store_selected_list").on("click",'[nctype="btn_store_select"]',function(){$(this).parents("li").remove()});$("#btn_module_store_save").click(function(){$("#store_selected_list").find("[nctype='btn_store_select']").remove();object_module_edit.html("");object_module_edit.html($("#store_selected_list").html());$("#dialog_module_store_edit").hide()});$("#member_selected_list").sortable();$("[nctype='btn_module_member_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");var e=object_module_edit.clone();e.find("li").each(function(){$(this).find("dl").append('<dd nctype="btn_member_select" class="handle-button" title="删除"></dd>')});$("#dialog_module_member_edit").feiwa_show_dialog({width:640,title:"编辑会员"});$("#member_selected_list").html(e.html());$("#member_search_list").html("")});$("#btn_member_search").click(function(){var e=$("#input_member_search_keyword").val();if(e!==""){$("#div_member_select_list").load("index.php?app=reads_index&feiwa=get_member_list&"+$.param({search_keyword:e}))}});$("#div_member_select_list").on("click",".demo",function(){$("#div_member_select_list").load($(this).attr("href"));return false});$("#div_member_select_list").on("click",'[nctype="btn_member_select"]',function(){var e=$("#article_selected_list li").length;var t=$(this).parents("li").clone();t.find("[nctype='btn_member_select']").attr("title","删除");$("#member_selected_list").append($("<div />").append(t).html())});$("#member_selected_list").on("click",'[nctype="btn_member_select"]',function(){$(this).parent("li").remove()});$("#btn_module_member_save").click(function(){$("#member_selected_list").find("[nctype='btn_member_select']").remove();object_module_edit.html("");object_module_edit.html($("#member_selected_list").html());$("#dialog_module_member_edit").hide()});$("[nctype='btn_module_flash_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");$("#dialog_module_flash_edit").feiwa_show_dialog({width:640,title:"编辑FLASH"})});$("#btn_module_flash_save").click(function(){var e={};e.address=$("#input_flash_address").val();e.width=$("#input_flash_width").val();e.height=$("#input_flash_height").val();object_module_edit.html(template.render("module_assembly_flash_template",e));$("#dialog_module_flash_edit").hide()});$("[nctype='btn_module_html_edit']").click(function(){object_module_edit=$(this).parent().parent().find("[nctype='object_module_edit']");$("#dialog_module_html_edit").feiwa_show_dialog({width:680,title:"编辑自定义块"});var e=KindEditor.create("#textarea_module_html",{items:["source","|","fullscreen","undo","redo","cut","copy","paste","|","fontname","fontsize","forecolor","hilitecolor","bold","italic","underline","removeformat","justifyleft","justifycenter","justifyright","insertorderedlist","insertunorderedlist","|","emoticons","image","link","|","about"],allowImageUpload:false,allowFlashUpload:false,allowMediaUpload:false,allowFileManager:false,filterMode:false,syncType:"form",afterCreate:function(){this.sync()},afterChange:function(){this.sync()},afterBlur:function(){this.sync()}});e.html(object_module_edit.html())});$("#btn_module_html_save").click(function(){object_module_edit.html($("#textarea_module_html").val());KindEditor.remove("#textarea_module_html");$("#dialog_module_html_edit").hide()});$("#btn_module_save").click(function(){$("[nctype='object_module_edit']").each(function(){$("#add_form").append('<input name="'+$(this).attr("id")+'" type="hidden" value="" />');$(this).find("[nctype='reads_index_not_save']").remove();$("#add_form input").last().val($(this).html())});$("#add_form").submit()})});$(window).load(function(){$(".micro-api-personal-list .t-img").VMiddleImg({width:170,height:220});$(".reads-index-module-article1-1 .t-img").VMiddleImg({width:380,height:210});$(".reads-index-module-article1-3 .t-img").VMiddleImg({width:78,height:78});$(".reads-index-module-article2-1 .t-img").VMiddleImg({width:160,height:120});$(".reads-index-module-article2-2 .t-img").VMiddleImg({width:160,height:120});$(".reads-index-module-article2-3 .t-img").VMiddleImg({width:78,height:38});$(".reads-module-assembly-article-image .t-img").VMiddleImg({width:160,height:120});$(".reads-module-assembly-brand .t-img").VMiddleImg({width:80,height:40});$("#block1_goods_content .t-img").VMiddleImg({width:148,height:148});$("#block2_goods_content .t-img").VMiddleImg({width:148,height:148});$("#block3_goods_content .t-img").VMiddleImg({width:148,height:148});$("#block4_article_content .t-img").VMiddleImg({width:148,height:148})});