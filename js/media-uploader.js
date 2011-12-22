/**
 *  add the media image uploader
 */

jQuery(document).ready(function($){
	var fromfield = null;
	$('#wp_add_meta_image').click(function(){
		$('html').addClass('Image');
		formfield = $('#wp_add_meta_image').attr('name');
		
		//using wp ui library function
		tb_show('','media-upload.php?type=image&TB_iframe=true');
		return false;
	});
	
	//saving default object
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){
		var fileurl;
		if(formfield != null){
			fileurl = $('img',html).attr('src');
			$('#wp_add_meta_image').val(fileurl);
			tb_remove();
			$('html').removeClass('Image');
			formfield = null;
		}
		else{
			window.original_send_to_editor(html);
		}
	}
});
