jQuery(function($){
	
	// Shut up.
});

function save_uploaded_files(form) {

	//console.log(form);
	var is_validated = validate_file_data();
	if ( is_validated ) {
		jQuery(form).find("#nm-saving-file").html(
				'<img src="' + nm_filemanager_vars.doing + '">');

		var data = jQuery(form).serialize();
		data = data + '&action=nm_filemanager_save_file_data';
		
		//console.log(data); return false;

		jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp) {

			//console.log(resp); return false;
			
			if(resp.status == 'error'){
				jQuery(form).find("#nm-saving-file").html(resp.message).css('color', 'red');
			}else{
				if(get_option('_redirect_url') != '')
					window.location = get_option('_redirect_url');
				else{
					jQuery(form).find("#nm-saving-file").html(resp.message).css('color', 'green');
					window.location.reload(true);
				}
			}
		}, 'json');

	} 
	return false;
}


/**
 * validating the file data
 * return true if ok
 */
function validate_file_data(){
	var total_files = jQuery('input:checkbox[name^="uploaded_files"]').length;
	var title_text = jQuery('.filelist').find('input[type="text"]');
	var is_ok = true;

	if( !get_option('_min_files') == '' && total_files < get_option('_min_files') ){
		is_ok = false;
		alert('You must upload atleast '+get_option('_min_files')+' files.');
	} else {	
		jQuery.each(title_text, function(i, item){
			
			jQuery(item).css({'border-color':'#000'});
			
			if( jQuery(item).val() == ''){
				is_ok = false;
				jQuery(item).css({'border-color':'#ff0000'});
			}
		});
	}
	return is_ok;
}

/**
 * saving file meta 
 */
function update_file_data(form) {

	//console.log(form);
	jQuery(form).find("#nm-saving-file-meta").html(
			'<img src="' + nm_filemanager_vars.doing + '">');
	
	var is_ok = validate_update_data(form);
	var file_ok = true;
	
	if (is_ok && file_ok) {

		var data = jQuery(form).serialize();
		data = data + '&action=nm_filemanager_update_file_data';
		
		jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp) {

			//console.log(resp); return false;
			
			if(resp.status == 'error'){
				jQuery(form).find("#nm-saving-file-meta").html(jQuery('input:hidden[name="_error_message"]').val()).css('color', 'red');
			}else{
				if(get_option('_redirect_url') != '')
					window.location = get_option('_redirect_url');
				else
					jQuery(form).find("#nm-saving-file-meta").html(resp.message).css('color', 'green');
				
				
			}
		}, 'json');

	} else {

		//show all sections if hidden
		jQuery(".nm-filemanager-box section").slideDown(200);
		
		jQuery(form).find("#nm-saving-file")
				.html('Please remove above Errors').css('color', 'red');
	}

	return false;
}

function validate_update_data(form){
	
	var form_data = jQuery.parseJSON( jQuery(form).attr('data-form') );
	var has_error = true;
	var error_in = '';
	
	jQuery.each( form_data, function( key, meta ) {
		
		var type = meta['type'];
		var error_message	= stripslashes( meta['error_message'] );
		
		console.log('typ e'+type+' error message '+error_message+'\n\n');
		  
		if(type === 'text' || type === 'textarea' || type === 'select' || type === 'email' || type === 'date'){
			
			var input_control = jQuery('#'+meta['data_name']);
			
			if(meta['required'] === "on" && jQuery(input_control).val() === ''){
				jQuery(input_control).closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
				error_in = meta['data_name']
			}else{
				jQuery(input_control).closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
			}
		}else if(type === 'checkbox'){
			
			//console.log('im error in cb '+error_message);	
			if(meta['required'] === "on" && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length === 0){
				
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else if(meta['min_checked'] != '' && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length < meta['min_checked']){
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else if(meta['max_checked'] != '' && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length > meta['max_checked']){
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else{
				
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
				
				}
		}else if(type === 'radio'){
				
				if(meta['required'] === "on" && jQuery('input:radio[name="'+meta['data_name']+'"]:checked').length === 0){
					jQuery('input:radio[name="'+meta['data_name']+'"]').closest('div').find('span.errors').html(error_message).css('color', 'red');
					has_error = false;
					error_in = meta['data_name']
				}else{
					jQuery('input:radio[name="'+meta['data_name']+'"]').closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
				}
		}else if(type === 'masked'){
			
			var input_control = jQuery('#'+meta['data_name']);
			
			if(meta['required'] === "on" && (jQuery(input_control).val() === '' || jQuery(input_control).attr('data-ismask') === 'no')){
				jQuery(input_control).closest('div').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
				error_in = meta['data_name'];
			}else{
				jQuery(input_control).closest('div').find('span.errors').html('').css({'border' : '','padding' : '0'});
			}
		}
		
	});
	
	//console.log( error_in ); return false;
	return has_error;
}

/**
 * this function extract values from setting 
 */
function get_option(key) {

	/*
	 * TODO: change plugin shortname
	 */
	var keyprefix = 'nm_filemanager';

	key = keyprefix + key;

	var req_option = '';

	jQuery.each(nm_filemanager_vars.settings, function(k, option) {

		// console.log(k);

		if (k == key)
			req_option = option;
	});

	// console.log(req_option);
	return req_option;
}
/**
 * this function confirms before deleting file 
 */
function confirmFirstDelete(url)
{
	var a = confirm('Are you sure to delete this file?');
	if(a)
	{
		window.location = url;
	}
}

/* sharing file with thick box dialog */
function share_file( file_name ){
	
var uri_string = encodeURI('action=nm_filemanager_share_file&width=800&height=500&filename='+file_name);
	
	var url = nm_filemanager_vars.ajaxurl + '?' + uri_string;
	tb_show(nm_filemanager_var.share_file_heading, url);
}

/* sharing file ajax function */
function send_files_email(form) {
	//console.log(form);
	jQuery("#sending-mail").show();
		if (jQuery("#shared_single_file").val() != "") 
			var files_to_send = jQuery("#shared_single_file").val();
		else
			var files_to_send = jQuery("#file-names").val();
			
		var data = {
			action: 'nm_filemanager_send_files_email',
			file_names: files_to_send,
			subject: jQuery("#subject").val(),
			email_to: jQuery("#email-to").val(),
			file_msg: jQuery("#file-msg").val()
		};
		//alert("done");
		jQuery.post(nm_filemanager_vars.ajaxurl, data, function(resp) {
			jQuery("#sending-mail").hide();
			alert(resp); 
			location.reload(true);
			//return false;
			
			
		});

	return false;
}


/* edit file meta with thick box dialog */
function edit_file_meta(postid){
	
var uri_string = encodeURI('action=nm_filemanager_edit_file_meta&width=800&postid='+postid);
	
	var url = nm_filemanager_vars.ajaxurl + '?' + uri_string;
	tb_show(nm_filemanager_vars.file_meta_heading, url);
}



function stripslashes (str) {
	  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // +   improved by: Ates Goral (http://magnetiq.com)
	  // +      fixed by: Mick@el
	  // +   improved by: marrtins
	  // +   bugfixed by: Onno Marsman
	  // +   improved by: rezna
	  // +   input by: Rick Waldron
	  // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
	  // +   input by: Brant Messenger (http://www.brantmessenger.com/)
	  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	  // *     example 1: stripslashes('Kevin\'s code');
	  // *     returns 1: "Kevin's code"
	  // *     example 2: stripslashes('Kevin\\\'s code');
	  // *     returns 2: "Kevin\'s code"
	  return (str + '').replace(/\\(.?)/g, function (s, n1) {
	    switch (n1) {
	    case '\\':
	      return '\\';
	    case '0':
	      return '\u0000';
	    case '':
	      return '';
	    default:
	      return n1;
	    }
	  });
	}

function get_dt_options(){
	
	var dt_options = { paging: false,
			 searching: false,
			 ordering:  false};
	
	return dt_options;
}