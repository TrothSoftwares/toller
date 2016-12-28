<?php
/*
 * it is loading all other templates
 */

global $nmfilemanager;
?>

<style>
	<?php echo $nmfilemanager -> load_template('_template_main_style.css');?>
</style>
	
<?php 
/*
 * loading uploader template
 */
 	
		$nmfilemanager -> load_template( '_template_uploader.php' );	
	
	
/*
 * loading uploaded files (list files) template
 */

		$nmfilemanager -> load_template( '_template_list_files.php' );

?>