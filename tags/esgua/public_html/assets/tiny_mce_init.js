<script type="text/javascript">
tinyMCE_GZ.init({
	plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,insertdatetime,preview,media,'+
        'searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
	themes : 'simple,advanced',
	disk_cache : true,
	content_css : "/css/site_tinymce.css",
    language : 'ru',
	debug : false
});
</script>
<script type="text/javascript">
tinyMCE.init({
		// General options
        mode : "textareas",
        language : 'ru',
		theme : "advanced",
        skin : "o2k7",
        skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

        // Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview",
		theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,ltr,rtl,|,fullscreen,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,

  		relative_urls : false,
		remove_script_host : true,
        file_browser_callback : "tinyBrowser",

		// Example content CSS (should be your site CSS)
		content_css : "/css/site_tinymce.css"

});
</script>