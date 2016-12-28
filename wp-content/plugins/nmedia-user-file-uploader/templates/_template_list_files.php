<?php
/*
 * it is loading all files uploaded by user
 */

global $nmfilemanager, $wpdb;

/** migrating old files if any **/
$nmfilemanager -> migrate_files();

/** getting the parameters for delete file **/
if(isset($_GET['pid']) && isset($_GET['do']) == 'delete')
{
	$nmfilemanager -> delete_file();
}

$login_user_id = get_current_user_id();
$allow_public = $nmfilemanager -> get_option('_allow_public');
if ($login_user_id == 0 && $allow_public[0] == 'yes')
	$login_user_id = $nmfilemanager -> get_option('_public_user');

$range = 2;
$showitems = ($range * 2)+1;  
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
			'orderby'          => 'post_title',
			//'order'            => $post_order,
			'post_type'        => 'nm-userfiles',
			'post_status'      => 'publish',
			//'paged'			   => $paged,
			'nopaging'		   => true,
			'author'           => $login_user_id,
/*			'meta_query' 	   => array( 
										array('key' => 'uploaded_file_names',
											  'value' => $search_str,
											  'compare' => 'LIKE')
										)
*/			);
		
?>
<table id="user-files" class="display">
<thead>
	<tr>
        <th>
        	<strong><?php _e('Thumb', 'nm-filemanager')?></strong>
        </th>
        <th>
        	<strong><?php _e('File Title', 'nm-filemanager')?></strong>
        </th>
        <th>
        	<strong><?php _e('Uploaded on', 'nm-filemanager')?></strong>
        </th>
        <th>
        	<strong><?php _e('File Tools', 'nm-filemanager')?></strong>
        </th>
    </tr>
</thead>
<tbody>
<!--<div id="nm-uploaded-files">-->
    <h2>File's uploaded</h2>
	<?php
		$my_query = new WP_Query();
		$query_posts = $my_query->query($args);
		
		$my_query1 = new WP_Query($args);
		$pages = $my_query1->max_num_pages;
			if(!$pages)
    			$pages = 1;

		while ( $my_query->have_posts() ) : 
		  		$my_query->the_post();

        $params = array('pid'	=> get_the_ID(),
						'do'	=> 'delete');
        $private_download = array('file_name'	=> $nmfilemanager -> get_attachment_file_name( get_the_ID() ),
		 						  'do'			=> 'download');

		$urlDelete = $nmfilemanager -> fixRequestURI($params);
		$urlPrivateDownload = $nmfilemanager -> fixRequestURI($private_download);		
	$meta = get_post_meta(get_the_ID(), '_wp_attachment_metadata', true);
	$file_meta = json_decode($meta, true);
	?>
      <tr id="file-list-row-<?php echo get_the_ID();?>">
	    <td>
			<?php     echo '<div id="file-list-row-'.get_the_ID().'" class="file-list-container">';
    	echo '<div id="file-thumb-'.get_the_ID().'" class="nm-file-thumb">';
    		$nmfilemanager -> set_file_download( get_the_ID() );
        echo '</div>';
		
        /**
         * rendering file title, description
         */ 
        //$nmfilemanager -> render_file_title_description( $file );
		$current_user = wp_get_current_user();
			echo '<div id="file-title-'.get_the_ID().'" class="nm-file-title">';
			//echo '<span class="rendering-file-title">'. the_title() .'</span>';
			//echo '<em> File uploaded by ' .$current_user->user_login.' '.get_the_date(). '</em>';
			//echo '<em> File uploaded '.$nmfilemanager -> time_difference( get_the_date() ).' by ' .$current_user->user_login. '</em>';
			echo '</div>';	
		 ?>
		</td>
        <td>
        	<?php echo '<span class="rendering-file-title">'. the_title() .'</span>'; ?>
        </td>
        <td>
        	<?php echo '<span class="rendering-file-title">'. get_the_date() .'</span>'; ?>
        </td>
    	<td>
			<?php         echo '<div id="file-tools-'.get_the_ID().'" class="nm-file-tools">';
            
            /*
             * rendering file tootls, edit, sharing, delete etc
             */
            //$nmfilemanager -> render_file_tools( $file );
			echo '<a title="'.__('Download file', 'nm-filemanager').'" href="'.$urlPrivateDownload.'"><img alt="'.__('Download file', 'nm-filemanager').'" src="'.$this -> plugin_meta['url'].'/images/download.png" /></a>';
			
			echo '<a title="'.__('Delete file', 'nm-filemanager').'" href="javascript:confirmFirstDelete('."'".$urlDelete."'".')"><img alt="'.__('Delete file', 'nm-filemanager').'" src="'.$this -> plugin_meta['url'].'/images/delet.png" /></a>';
        echo '</div>';
        echo '<div class="clear"></div>';
    echo '</div>';
 ?>
		</td>
	  </tr>

	<?php


	endwhile;
	?>
<!--</div>-->
<!--Pagination code start here-->
<div id="filemanager-pagination"> 
<?php //echo $pages.' '.$paged.' '.$range.' '.$showitems.' ljkljklj '.$my_query1->max_num_pages;
/*if(1 != $pages)
{
    echo "<div class='pagination'>";
    if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
    if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

    for ($i=1; $i <= $pages; $i++)
    {
        if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
        {
            echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
        }
    }
    if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
    if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
    echo "</div>\n";
}*/	
?>
</div>       
 <!--Pagination code end here-->
 
 </tbody>
</table>
               

<script type="text/javascript"><!--

jQuery(document).ready(function($){
	$('#user-files').dataTable(get_dt_options());
} );
//-->
</script>

<?php
/**
 * adding thickbox support for image larg view
 */
 add_thickbox();