<?php
    /*
    Plugin Name: TollerApp
    Plugin URI: http://www.meembit.com
    Description: Plugin for implementing toller app functions
    Author: Mohammed Talha
    Version: 1.0
    Author URI: http://www.alfikri.in
    */

    // Removes from admin menu



    add_action( 'admin_menu', 'my_remove_admin_menus' );
    function my_remove_admin_menus() {
        remove_menu_page( 'edit-comments.php' );
    }
    // Removes from post and pages
    add_action('init', 'remove_comment_support', 100);

    function remove_comment_support() {
        remove_post_type_support( 'post', 'comments' );
        remove_post_type_support( 'page', 'comments' );
    }
    // Removes from admin bar
    function mytheme_admin_bar_render() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    }
    add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );









function toller_admin() {
    include('toller.php');
}
function toller_admin_actions() {
    add_menu_page("Toller", "Toller" ,'read', "Toller", "toller_admin");
}
add_action('admin_menu', 'toller_admin_actions');

define('TOLLER_PLUGIN_URL', plugin_dir_url( __FILE__ ));


function timepicker(){
  wp_register_style('kv_js_time_style' , TOLLER_PLUGIN_URL. 'css/jquery.timepicker.css');
	wp_enqueue_style('kv_js_time_style');
  wp_enqueue_script('time-picker',TOLLER_PLUGIN_URL. 'js/jquery.timepicker.js');

wp_enqueue_script('jquery-ui-datepicker');
wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
wp_enqueue_style('jquery-ui');

}

add_action('admin_head', 'timepicker');





if(isset($_POST['Scheduleset1'])){
  // print_r($_POST);
  // exit;

   $serializedpost =  serialize($_POST);
   $my_post = array(
       'ID'           => $_POST['postid'],
       'post_content' => $serializedpost,
   );

 // Update the post into the database
   wp_update_post( $my_post );
  //  $serializedpost =  serialize($_POST);

}







//*****************   CODE FOR USER INFO WIDGET  ***********//



// Creating the widget
class wpb_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
    // Base ID of your widget
    'wpb_widget',

    // Widget name will appear in UI
    __('User Info Widget', 'wpb_widget_domain'),

    // Widget description
    array( 'description' => __( 'Widget displaying user information', 'wpb_widget_domain' ), )
  );
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
  $title = apply_filters( 'widget_title', $instance['title'] );
  // before and after widget arguments are defined by themes
  echo $args['before_widget'];
  if ( ! empty( $title ) )
  echo $args['before_title'] . $title . $args['after_title'];

  // This is where you run the code and display the output
  $userid = get_current_user_id();
    $userdata = ( get_userdata($userid));

    // print_r($userdata);

    echo '<table>
    <tr>
    <td>Name:</td>
    <td>'.$userdata->display_name.'</td>
    </tr>
    <tr>
    <td>Type</td>
    <td></td>
    </tr>
    <tr>
    <td>Address :</td>
    <td>'.get_the_author_meta( 'address', $userid ).'</td>
    </tr>
    <tr>
    <td>Date of Installation:</td>
    <td>'.get_the_author_meta( 'dateofinstallation', $userid ).'</td>
    </tr>
    <tr>
    <td>Contact:</td>
    <td>'.get_the_author_meta( 'contact1', $userid ).'<br>'.get_the_author_meta( 'contact2', $userid ).'</td>
    </tr>
    <tr>
    <td>Warranty</td>
    <td>'.get_the_author_meta( 'warranty', $userid ).'</td>
    </tr>
    <tr>
    <td>User Status</td>
    <td>'.get_the_author_meta( 'userstatus', $userid ).'<br>'.get_the_author_meta( 'userstatus', $userid ).'</td>
    </tr>
    </table>
    ';


   echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
  if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
  }
  else {
    $title = __( 'New title', 'wpb_widget_domain' );
  }
  // Widget admin form
  ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
  $instance = array();
  $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
  return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
  register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );


//************ END OF CODE FOR USER INFO WIDGET  ****************//






//************ START OF CODE FOR CUSTOM FIELDS IN USER REGISTRATION  ****************//




function custom_user_profile_fields($user){
   ?>
   <h3>Extra profile information</h3>
   <table class="form-table">
       <tr>
           <th><label for="address">address</label></th>
           <td>
               <input type="text" class="regular-text" name="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" id="address" /><br />
           </td>
       </tr>

       <tr>
           <th><label for="contact1">Contact No 1:</label></th>
           <td>
               <input type="text" class="regular-text" name="contact1" value="<?php echo esc_attr( get_the_author_meta( 'contact1', $user->ID ) ); ?>" id="contact1" /><br />
           </td>
       </tr>
       <tr>
           <th><label for="contact2">Contact No 2:</label></th>
           <td>
               <input type="text" class="regular-text" name="contact2" value="<?php echo esc_attr( get_the_author_meta( 'contact2', $user->ID ) ); ?>" id="contact2" /><br />
           </td>
       </tr>
       <tr>
           <th><label for="dyndns">Dyn DNS</label></th>
           <td>
               <input type="text" class="regular-text" name="dyndns" value="<?php echo esc_attr( get_the_author_meta( 'dyndns', $user->ID ) ); ?>" id="dyndns" /><br />
           </td>
       </tr>
         <tr>
             <th><label for="port">Port</label></th>
             <td>
                 <input type="text" class="regular-text" name="port" value="<?php echo esc_attr( get_the_author_meta( 'port', $user->ID ) ); ?>" id="port" /><br />
             </td>
         </tr>
         <tr>
             <th><label for="dateofinstallation">Date Of Installation</label></th>
             <td>
                 <input type="date" class="regular-text" name="dateofinstallation" value="<?php echo esc_attr( get_the_author_meta( 'dateofinstallation', $user->ID ) ); ?>" id="dateofinstallation" /><br />


             </td>
         </tr>
         <tr>
             <th><label for="warranty">Warranty Period</label></th>
             <td>
                 <input type="text" class="regular-text" name="warranty" value="<?php echo esc_attr( get_the_author_meta( 'warranty', $user->ID ) ) ? esc_attr( get_the_author_meta( 'warranty', $user->ID ) ):'18 Months';  ?>" id="warranty" /><br />
             </td>
         </tr>
         <tr>
             <th><label for="userstatus">User Status</label></th>
             <td>
                 <input type="checkbox" class="regular-text" name="userstatus" checked="<?php echo esc_attr( get_the_author_meta( 'userstatus', $user->ID ) )  == 'on' ? 'true':'false'  ?>" id="userstatus" /><br />
             </td>
         </tr>
   </table>
<?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields' );
add_action( 'edit_user_profile', 'custom_user_profile_fields' );
add_action( "user_new_form", "custom_user_profile_fields" );

function save_custom_user_profile_fields($user_id){
   # again do this only if you can
   if(!current_user_can('manage_options'))
       return false;



   # save my custom field
   update_usermeta($user_id, 'company', $_POST['company']);
   update_usermeta($user_id, 'address', $_POST['address']);
   update_usermeta($user_id, 'contact1', $_POST['contact1']);
   update_usermeta($user_id, 'contact2', $_POST['contact2']);
   update_usermeta($user_id, 'dyndns', $_POST['dyndns']);
   update_usermeta($user_id, 'port', $_POST['port']);
   update_usermeta($user_id, 'dateofinstallation', $_POST['dateofinstallation']);
   update_usermeta($user_id, 'warranty', $_POST['warranty']);
   update_usermeta($user_id, 'userstatus', $_POST['userstatus']);

}
add_action('user_register', 'save_custom_user_profile_fields');


//************ END OF  CODE FOR CUSTOM FIELDS IN USER REGISTRATION  ****************//








/****************** START OF CODE FOR MEDIA UPLOADER IN FRONTEND   ************/


add_action( 'wp_enqueue_scripts',  'enqueue_scripts' ) ;
add_filter( 'ajax_query_attachments_args',  'filter_media' ) ;
add_shortcode( 'the_dramatist_front_upload',  'the_dramatist_front_upload' ) ;

/**
 * Call wp_enqueue_media() to load up all the scripts we need for media uploader
 */
function enqueue_scripts() {

    wp_enqueue_media();
    wp_enqueue_script(
        'some-script',
        get_template_directory_uri() . '/js/media-uploader.js',
        // if you are building a plugin
        // plugins_url( '/', __FILE__ ) . '/js/media-uploader.js',
        array( 'jquery' ),
        null
    );
}
/**
 * This filter insures users only see their own media
 */
function filter_media( $query ) {
    // admins get to see everything
    if ( ! current_user_can( 'manage_options' ) )
        $query['author'] = get_current_user_id();
    return $query;
}
function the_dramatist_front_upload( $args ) {
    // check if user can upload files


     if ( current_user_can( 'upload_files' ) ) {

    return '
    <button  id="frontend-button" class="btn btn-default pull-right" type="button">
      <em class="glyphicon glyphicon-folder-open"></em> Audio Library
    </button>';


  }
    return __( 'Please Login To Upload', 'frontend-media' );
}


/****************** END FOR CODE FOR MEDIA UPLOADER IN FRONTEND     ******/
