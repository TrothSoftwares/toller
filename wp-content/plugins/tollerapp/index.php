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
  <tr style="display:none">
  <td>User Status</td>
  <td>'.get_the_author_meta( 'userstatus', $userid ).'</td>
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
    <?php /*
    <tr>
    <th><label for="userstatus">User Status</label></th>
    <td>
    <input type="checkbox" class="regular-text" name="userstatus" checked="<?php echo esc_attr( get_the_author_meta( 'userstatus', $user->ID ) )  == 'on' ? 'true':'false'  ?>" id="userstatus" /><br />
    </td>
    </tr>

    */ ?>
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

  $scheduleset1_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';


  $scheduleset1defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset1_postcontent,
    'post_title' => 'Scheduleset-1',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )

  );


  $scheduleset2_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';


  $scheduleset2defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset2_postcontent,
    'post_title' => 'Scheduleset-2',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )

  );


  $scheduleset3_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $scheduleset3defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset3_postcontent,
    'post_title' => 'Scheduleset-3',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )
  );


  $scheduleset4_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $scheduleset4defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset4_postcontent,
    'post_title' => 'Scheduleset-4',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )
  );

  $scheduleset5_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $scheduleset5defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset5_postcontent,
    'post_title' => 'Scheduleset-5',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )
  );

  $scheduleset6_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $scheduleset6defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset6_postcontent,
    'post_title' => 'Scheduleset-6',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )
  );

  $scheduleset7_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $scheduleset7defaults = array(
    'post_author' => $user_id,
    'post_content' => $scheduleset7_postcontent,
    'post_title' => 'Scheduleset-7',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(2) )
  );

  $exam_scheduleset1_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $exam_scheduleset1defaults = array(
    'post_author' => $user_id,
    'post_content' => $exam_scheduleset1_postcontent,
    'post_title' => 'Exam-Scheduleset-1',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(3) )
  );


  $exam_scheduleset2_postcontent = 'a:4:{s:6:"postid";s:1:"7";s:11:"assignation";s:0:"";s:6:"submit";s:13:"Scheduleset-1";s:7:"timings";a:25:{i:1;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:2;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:3;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:4;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:5;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:6;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:7;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:8;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:9;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:10;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:11;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:12;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:13;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:14;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:15;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:16;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:17;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:18;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:19;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:20;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:21;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:22;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:23;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:24;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}i:25;a:2:{s:4:"time";s:0:"";s:4:"file";s:0:"";}}}';

  $exam_scheduleset2defaults = array(
    'post_author' => $user_id,
    'post_content' => $exam_scheduleset2_postcontent,
    'post_title' => 'Exam-Scheduleset-2',
    'post_status' => 'publish',
    'post_type' => 'post',
    'tax_input' => array( 'category' => array(3) )
  );



  wp_insert_post( $scheduleset1defaults );
  wp_insert_post( $scheduleset2defaults );
  wp_insert_post( $scheduleset3defaults );
  wp_insert_post( $scheduleset4defaults );
  wp_insert_post( $scheduleset5defaults );
  wp_insert_post( $scheduleset6defaults );
  wp_insert_post( $scheduleset7defaults );
  wp_insert_post( $exam_scheduleset1defaults );
  wp_insert_post( $exam_scheduleset2defaults );

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





















/********************** START OF MEMBERS ONLY PLUGIN    *******************/


register_activation_hook(__FILE__,'members_only_setup_options');

//Members Only Options
$members_only_opt = get_option('members_only_options');

//Get the page that was originally requested by the user
$members_only_reqpage = $_SERVER["REQUEST_URI"];

//Setup Feedkey Variables
$feedkey_valid = FALSE;
$feed_redirected = FALSE;

//Get WordPress URLs and Title
$blogurl = get_bloginfo('url');
$wpurl = get_bloginfo('wpurl');
$blogtitle = get_bloginfo('title');

//Get the current URL
$currenturl = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

//----------------------------------------------------------------------------
//	Error Messages
//----------------------------------------------------------------------------

$errormsg = array(
  'feedkey_invalid' => 'The Feed Key you used is invalid. It is either incorrect or has been revoked. Please login to obtain a valid Feed Key.',
  'feedkey_missing' => 'You need to use a Feed Key to access feeds on this site. Please login to obtain yours.',
  'feedkey_notgen' => 'Feed Key not found.',
  'feedurl_notgen' => 'URL is available once Feed Key has been generated.'
);

//----------------------------------------------------------------------------
//	Setup Default Settings
//----------------------------------------------------------------------------

function members_only_setup_options()
{
  global $members_only_opt;

  $members_only_version = get_option('members_only_version'); //Members Only Version Number
  $members_only_this_version = '0.6.7';

  // Check the version of Members Only
  if (empty($members_only_version))
  {
    add_option('members_only_version', $members_only_this_version);
  }
  elseif ($members_only_version != $members_only_this_version)
  {
    update_option('members_only_version', $members_only_this_version);
  }

  // Setup Default Options Array
  $optionarray_def = array(
    'members_only' => FALSE,
    'redirect_to' => 'login',
    'login_redirect_to' => 'dashboard',
    'redirect_url' => '',
    'redirect' => TRUE,
    'feed_access' => 'feedkeys',
    'feedkey_reset' => TRUE,
    'require_feedkeys' => FALSE,
    'one_time_view_ip' => NULL
  );

  if (empty($members_only_opt)){ //If there aren't already options for Members Only
    add_option('members_only_options', $optionarray_def, 'Members Only Wordpress Plugin Options');
  }
}

//Detect WordPress version to add compatibility with 2.3 or higher
$wpversion_full = get_bloginfo('version');
$wpversion = preg_replace('/([0-9].[0-9])(.*)/', '$1', $wpversion_full); //Boil down version number to X.X

//--------------------------------------------------------------------------
//	Add Admin Page
//--------------------------------------------------------------------------

function members_only_add_options_page()
{
  if (function_exists('add_options_page'))
  {
    add_options_page('Members Only', 'Members Only', 8, basename(__FILE__), 'members_only_options_page');
  }
}

//---------------------------------------------------------------------------
//	Add Feed Key to Profile Page
//---------------------------------------------------------------------------

function members_only_display_feedkey()
{
  global $profileuser, $current_user, $blogurl, $members_only_opt, $errormsg;

  // Setup Feed Key Reset Options
  $feedkey_reset_types = array(
    'Feed Key Options...' => NULL,
    'Reset Feed Key' => 'feedkey-reset',
    'Remove Feed Key' => 'feedkey-remove'
  );

  foreach ($feedkey_reset_types as $option => $value) {
    if ($value == $optionarray_def['login_redirect_to']) {
      $selected = 'selected="selected"';
    } else {
      $selected = '';
    }

    $feedkey_reset_options .= "\n\t<option value='$value' $selected>$option</option>";
  }

  if ($members_only_opt ['feed_access'] == 'feedkeys') //Check if Feed Keys are being used
  {
    $yourprofile = $profileuser->ID == $current_user->ID;
    $feedkey = get_usermeta($profileuser->ID,'feed_key');
    $permalink_structure = get_option(permalink_structure);

    //Check if Permalinks are being used
    empty($permalink_structure) ? $feedjoin = '?feed=rss2&feedkey=' : $feedjoin = '/feed/?feedkey=';

    $feedurl = $blogurl.$feedjoin.$feedkey;
    $feedurl = '<a href="'.$feedurl.'">'.$feedurl.'</a>';

    ?>
    <table class="form-table">
      <h3><?php echo $yourprofile ? _e("Your Feed Key", 'feed-key') : _e("User's Feed Key", 'feed-key') ?></h3>
      <tr>
        <th><label for="feedkey">Feed Key</label></th>
        <td width="250px"><?php echo empty($feedkey) ? _e('<em>'.$errormsg['feedkey_notgen'].'</em>') : _e($feedkey); ?></td>
        <td>

          <?php if ($members_only_opt ['feedkey_reset'] == TRUE && !$current_user->has_cap('level_9')) : ?>
            <input name="feedkey-reset" type="checkbox" id="feedkey-reset_inp" value="0" /> Reset Key
          <?php elseif ($current_user->has_cap('level_9')) : ?>
            <?php if (empty($feedkey)) : ?>
              <input name="feedkey-generate" type="checkbox" id="feedkey-generate_inp" value="0" /> Generate Key
            <?php else : ?>
              <select name="feedkey-reset-admin" id="feedkey-reset-admin"><?php echo $feedkey_reset_options ?></select>
            <?php endif; ?>
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <th><label for="feedkey">Your Feed URL</label></th>
        <td colspan="2"><?php echo empty($feedkey) ? _e('<em>'.$errormsg['feedurl_notgen'].'</em>') : _e($feedurl); ?></td>
      </tr>
    </table>
    <?php
  }
}

//----------------------------------------------------------------------------
//		PLUGIN FUNCTIONS
//----------------------------------------------------------------------------

//----------------------------------------------------------------------------
//	Main Function
//----------------------------------------------------------------------------

function members_only()
{
  global $currenturl, $members_only_opt, $feedkey_valid, $errormsg, $userdata, $current_user, $wpurl;

  //Get Redirect
  $redirection = members_only_createredirect();

  if (md5($_SERVER['REMOTE_ADDR']) == $members_only_opt['one_time_view_ip'] && XMLRPC_REQUEST)	//Check for one-time allowed IP address
  {
    //Remove IP and Update Settings
    $members_only_opt['one_time_view_ip'] = NULL;
    update_option('members_only_options', $members_only_opt);

    //Do Nothing
  }
  elseif (empty($userdata->ID)) //Check if user is logged in
  {
    if (is_feed()) //Check if URL is a Feed
    {
      if (empty($_GET['feedkey']) && $members_only_opt['feed_access'] == 'feedkeys')
      {
        $feed = members_only_create_feed('No Feed Key Found', $errormsg['feedkey_missing']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feedkey_valid == FALSE && $members_only_opt['feed_access'] == 'feedkeys')
      {
        $feed = members_only_create_feed('Feed Key is Invalid', $errormsg['feedkey_invalid']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feedkey_valid == TRUE || $members_only_opt['feed_access'] == 'feednone')
      {
        // Do Nothing
      }
      else //Not using Feed Keys
      {
        members_only_redirect($redirection);
      }
    }	// Check if whether we are...
    elseif ($currenturl == $redirection || //...at the redirection page without a trailing slash
    $currenturl == $redirection.'/' //...at the redirection page with a trailing slash
    )
    {
      // Do Nothing
    }
    else
    {
      //Redirect Page
      members_only_redirect($redirection);
    }
  }
  else //User is logged in
  {
    if (is_feed() && $members_only_opt['feed_access'] == 'feedkeys' && $members_only_opt['require_feedkeys'] == TRUE) //If site requires Feed Keys for logged in users
    {
      if (empty($_GET['feedkey']))
      {
        $feed = members_only_create_feed('No Feed Key Found', $errormsg['feedkey_missing']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feedkey_valid == FALSE)
      {
        $feed = members_only_create_feed('Feed Key is Invalid', $errormsg['feedkey_invalid']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feedkey_valid == TRUE)
      {
        // Do Nothing
      }
    }
  }
}

//----------------------------------------------------------------------------
//	Init Function
//----------------------------------------------------------------------------

function members_only_init()
{
  global $userdata, $currenturl, $feedkey_valid, $feed_redirected, $errormsg, $members_only_opt, $wpdb;

  //Get Redirect
  $redirection = members_only_createredirect();

  //Parse URL
  $parsed_url = parse_url($currenturl);

  if (!empty($userdata->ID)) // If user is logged in
  {
    //Get User's Feed key
    $feedkey = get_usermeta($userdata->ID,'feed_key');

    //If there isn't one then generate one
    if (empty($feedkey))
    {
      $feedkey = members_only_gen_feedkey();
      update_usermeta($userdata->ID, 'feed_key', $feedkey);
    }
  }

  if (empty($userdata->ID) && $members_only_opt['feed_access'] != 'feednone')  //Check if user is logged in or Feed Keys is required
  {
    $feedkey = $_GET['feedkey'];

    if (!empty($feedkey))
    {
      // Check if Feed Key is in the Database
      $find_feedkey = $wpdb->get_results("SELECT umeta_id FROM $wpdb->usermeta WHERE meta_value = '$feedkey'");

      if (!empty($find_feedkey) && $members_only_opt['feed_access'] == 'feedkeys') //If Feed Key is found and using Feed Keys
      {
        $feedkey_valid = TRUE;
      }
    }

    //WordPress Feed Files
    switch (basename($_SERVER['PHP_SELF']))
    {
      case 'wp-rss.php':
      case 'wp-rss2.php':
      case 'wp-atom.php':
      case 'wp-rdf.php':
      case 'wp-commentsrss2.php':
      case 'wp-feed.php':
      if (empty($feedkey) && $members_only_opt['feed_access'] == 'feedkeys')
      {
        $feed = members_only_create_feed('No Feed Key Found', $errormsg['feedkey_missing']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feedkey_valid == FALSE && $members_only_opt['feed_access'] == 'feedkeys')
      {
        $feed = members_only_create_feed('Feed Key is Invalid', $errormsg['feedkey_invalid']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feed_redirected == FALSE && $members_only_opt['feed_access'] != 'feedkeys') //Not Using Feed Keys
      {
        members_only_redirect($redirection);
        $feed_redirected = TRUE;
      }
      break;
    }

    //WordPress Feed Queries
    switch ($_GET['feed'])
    {
      case 'rss':
      case 'rss2':
      case 'atom':
      case 'rdf':
      if (empty($feedkey) && $members_only_opt['feed_access'] == 'feedkeys')
      {
        $feed = members_only_create_feed('No Feed Key Found', $errormsg['feedkey_missing']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feedkey_valid == FALSE && $members_only_opt['feed_access'] == 'feedkeys')
      {
        $feed = members_only_create_feed('Feed Key is Invalid', $errormsg['feedkey_invalid']);
        header("Content-Type: application/xml; charset=ISO-8859-1");
        echo $feed;
        exit;
      }
      elseif ($feed_redirected == FALSE && $members_only_opt['feed_access'] != 'feedkeys') //Not Using Feed Keys
      {
        members_only_redirect($redirection);
        $feed_redirected = TRUE;
      }
      break;
    }
  }
}

//----------------------------------------------------------------------------
//	Create Redirect Function
//----------------------------------------------------------------------------

function members_only_createredirect()
{
  global $members_only_opt, $members_only_reqpage, $blogurl, $wpurl;

  //Check redirection settings
  //If redirecting to login page or specified page is blank
  if ($members_only_opt['redirect_to'] == 'login' || $members_only_opt['redirect_to'] == 'specifypage' && $members_only_opt['redirect_url'] == '')
  {
    $output = "/wp-login.php";

    if ($members_only_opt['redirect'] == TRUE) //If redirecting to original page after logging in
    {
      $output .= "?redirect_to=";
      $output .= $members_only_reqpage;
    }

    $output = $wpurl.$output;
  }
  elseif ($members_only_opt['redirect_to'] == 'specifypage' && $members_only_opt['redirect_url'] != '') //If redirecting to specific page
  {
    $output = '/'.$members_only_opt['redirect_url'];
    $output = $blogurl.$output;
  }

  return $output;
}

//----------------------------------------------------------------------------
//	Redirect Function
//----------------------------------------------------------------------------

function members_only_redirect($redirection)
{
  //Redirect Page
  if (function_exists('status_header')) status_header( 302 );
  header("HTTP/1.1 302 Temporary Redirect");
  header("Location:".$redirection);
  exit();
}

//----------------------------------------------------------------------------
//	Generate Feed Key Function
//----------------------------------------------------------------------------

function members_only_gen_feedkey()
{
  global $userdata;

  $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; //Key Character Set
  $keylength = 32; //Key Length

  for ($i=0; $i<$keylength; $i++)
  {
    $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];
  }

  //Hash key against user login to make sure no two users can ever have the same key
  $hashedkey = md5($userdata->user_login.$key);

  return $hashedkey;
}

//----------------------------------------------------------------------------
//	Reset Feed Key Function
//----------------------------------------------------------------------------

function members_only_reset_feedkey()
{
  $id = $_POST['user_id'];

  if ($_POST['feedkey-reset'] != NULL || $_POST['feedkey-generate'] != NULL || $_POST['feedkey-reset-admin'] == 'feedkey-reset') //If the reset or generate check box is checked
  {
    $feedkey = members_only_gen_feedkey();
    update_usermeta($id, 'feed_key', $feedkey);
  }

  if ($_POST['feedkey-reset-admin'] == 'feedkey-remove')
  {
    $feedkey = NULL;
    update_usermeta($id, 'feed_key', $feedkey);
  }
}

//----------------------------------------------------------------------------
//	Create RSS Feed Function
//----------------------------------------------------------------------------

function members_only_create_feed($item_title, $item_description)
{
  global $blogtitle, $blogurl;

  $today = date('F j, Y G:i:s T');

  $feed_content = '<?xml version="1.0" encoding="ISO-8859-1" ?>
  <rss version="2.0">
  <channel>
  <title>'.$blogtitle.'</title>
  <link>'.$blogurl.'</link>
  <item>
  <title>'.$item_title.'</title>
  <link>'.$blogurl.'</link>
  <description>'.$item_description.'</description>
  <pubDate>'.$today.'</pubDate>
  </item>
  </channel>
  </rss>';

  return $feed_content;
}

//----------------------------------------------------------------------------
//	Login Redirect Function
//----------------------------------------------------------------------------

function members_only_login_redirect() {
  global $redirect_to, $members_only_opt;

  if (!isset($_GET['redirect_to']) && $members_only_opt['login_redirect_to'] == 'frontpage')
  {
    $redirect_to = get_option('siteurl');
  }
}

//----------------------------------------------------------------------------
//		ADMIN OPTION PAGE FUNCTIONS
//----------------------------------------------------------------------------

function members_only_options_page()
{
  global $wpdb, $wpversion;

  if (isset($_POST['submit']) ) {

    if ($_POST['one_time_view_ip'] == 1)
    {

      $one_time_view_ip = md5($_SERVER['REMOTE_ADDR']);
    }
    else
    {
      $one_time_view_ip = NULL;
    }

    // Options Array Update
    $optionarray_update = array (
    'members_only' => $_POST['members_only'],
    'redirect_to' => $_POST['redirect_to'],
    'login_redirect_to' => $_POST['login_redirect_to'],
    'redirect_url' => $_POST['redirect_url'],
    'redirect' => $_POST['redirect'],
    'feed_access' => $_POST['feed_access'],
    'feedkey_reset' => $_POST['feedkey_reset'],
    'require_feedkeys' => $_POST['require_feedkeys'],
    'one_time_view_ip' => $one_time_view_ip
  );

  update_option('members_only_options', $optionarray_update);
}

// Get Options
$optionarray_def = get_option('members_only_options');

// Setup Redirection Options
$redirecttypes = array(
  'Login Page' => 'login',
  'Specific Page' => 'specifypage'
);

foreach ($redirecttypes as $option => $value) {
  if ($value == $optionarray_def['redirect_to']) {
    $selected = 'selected="selected"';
  } else {
    $selected = '';
  }

  $redirectoptions .= "\n\t<option value='$value' $selected>$option</option>";
}

// Setup Login Redirection Options
$loginredirecttypes = array(
  'Dashboard' => 'dashboard',
  'Front Page' => 'frontpage'
);

foreach ($loginredirecttypes as $option => $value) {
  if ($value == $optionarray_def['login_redirect_to']) {
    $selected = 'selected="selected"';
  } else {
    $selected = '';
  }

  $login_redirectoptions .= "\n\t<option value='$value' $selected>$option</option>";
}

// Setup Feed Access Options
$feedaccesstypes = array(
  'Use Feed Keys' => 'feedkeys',
  'Require User Login' => 'feedlogin',
  'Open Feeds' => 'feednone'
);

foreach ($feedaccesstypes as $option => $value) {
  if ($value == $optionarray_def['feed_access']) {
    $selected = 'selected="selected"';
  } else {
    $selected = '';
  }

  $feedprotectionoptions .= "\n\t<option value='$value' $selected>$option</option>";
}

?>
<div class="wrap">
  <h2>Members Only Options</h2>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>&updated=true">
    <fieldset class="options" style="border: none">
      <p>
        Checking the <em>Members Only</em> option below will make your blog only viewable to users that are logged in. If a visitor is not logged in,
        they will be redirected to the WordPress login page or a page that you can specify. Once logged in they can be redirected back to the page that they originally requested if you choose to.
      </p>
      <table width="100%" <?php $wpversion >= 2.5 ? _e('class="form-table"') : _e('cellspacing="2" cellpadding="5" class="editform"'); ?> >
        <tr valign="top">
          <th width="200px" scope="row">Members Only?</th>
          <td width="100px"><input name="members_only" type="checkbox" id="members_only_inp" value="1" <?php checked('1', $optionarray_def['members_only']); ?>"  /></td>
          <td><span style="color: #555; font-size: .85em;">Choose between making your blog only accessable to users that are logged in</span></td>
        </tr>
      </table>
    </p>
    <h3>Blog Access Options</h3>
    <table width="100%" <?php $wpversion >= 2.5 ? _e('class="form-table"') : _e('cellspacing="2" cellpadding="5" class="editform"'); ?> >
      <tr valign="top">
        <th scope="row">Redirect To</th>
        <td><select name="redirect_to" id="redirect_to_inp"><?php echo $redirectoptions ?></select></td>
        <td><span style="color: #555; font-size: .85em;">Choose where a user that isn't logged in is redirected to</span></td>
      </tr>
      <tr valign="top">
        <th width="200px" scope="row">Return User</th>
        <td width="100px"><input name="redirect" type="checkbox" id="redirect_inp" value="1" <?php checked('1', $optionarray_def['redirect']); ?>"  /></td>
        <td><span style="color: #555; font-size: .85em;">Choose whether once logged in, the user returns to the originally requested page <em>(Only applies if your redirecting to the login page)</em></span></td>
      </tr>
      <tr valign="top">
        <th scope="row">Redirection Page</th>
        <td colspan="2"><?php bloginfo('url');?>/<input type="text" name="redirect_url" id="redirect_url_inp" value="<?php echo $optionarray_def['redirect_url']; ?>" size="35" /><br />
          <span style="color: #555; font-size: .85em;">If the field is left blank, users will be redirected to the login page instead.
            <em>(Only applies if your redirecting to the specific page)</em></span></span>
          </td>
        </tr>
        <tr valign="top">
          <th width="200px" scope="row">Login Redirect</th>
          <td width="100px"><select name="login_redirect_to" id="login_redirect_to_inp"><?php echo $login_redirectoptions ?></select></td>
          <td><span style="color: #555; font-size: .85em;">Choose where the User is redirected to if they login directly from the login page.</span></td>
        </tr>
        <tr valign="top">
          <th scope="row">XML RPC Access</th>
          <td width="100px"><input name="one_time_view_ip" type="checkbox" id="one_time_view_ip_inp" value="1" <?php checked('1', $optionarray_def['one_time_view_ip']); ?>"  /></td>
          <td><span style="color: #555; font-size: .85em;">Allow a one-time view from <strong><span style="font-size: 1.2em;"><?php echo $_SERVER['REMOTE_ADDR'];?></span></strong>, to add your blog to an XML RPC application <em>(such a WordPress for iPhone)</em></span></span>
          </td>
        </tr>
      </table>
      <h3>Feed Access Options</h3>
      <p>
        <em>Members Only</em> can also protect your blog's feeds either by requiring a user to be logged in, or using <em>Feed Keys</em>. <em>Feed Keys</em> are unique 32bit keys that are created for every user on your site. This allows each user on your site to access your feeds using their own unique URL, so you can protect your feeds whilst still allowing your users to use other methods, such as feed readers, to access your feeds. Your users can also find their <em>Feed Key</em> in their profile page, and you can allow them to reset their <em>Feed Keys</em> if you choose.
      </p>
      <table width="100%" <?php $wpversion >= 2.5 ? _e('class="form-table"') : _e('cellspacing="2" cellpadding="5" class="editform"'); ?> >
        <tr valign="top">
          <th width="200"px" scope="row">Feed Access</th>
          <td width="100px"><select name="feed_access" id="feed_access_inp"><?php echo $feedprotectionoptions ?></select></td>
          <td><span style="color: #555; font-size: .85em;">Choose if Feeds are accessable, by using Feed Keys, User Login or Open Feeds to anyone.<br /></span></td>
        </tr>
        <tr valign="top">
          <th scope="row">Require Feed Keys</th>
          <td><input name="require_feedkeys" type="checkbox" id="require_feedkeys_inp" value="1" <?php checked('1', $optionarray_def['require_feedkeys']); ?>  /></td>
          <td><span style="color: #555; font-size: .85em;">Choose whether to always use Feed Keys even if user is logged in. <em>(Only applies if your using Feed Keys)</em></span></td>
        </tr>
        <tr valign="top">
          <th scope="row">User Reset</th>
          <td><input name="feedkey_reset" type="checkbox" id="feedkey_reset_inp" value="1" <?php checked('1', $optionarray_def['feedkey_reset']); ?> /></td>
          <td><span style="color: #555; font-size: .85em;">Choose whether users can reset their own Feed Keys. <em>(Only applies if your using Feed Keys)</em></span></td>
        </tr>
      </table>
    </fieldset>
    <p />
    <div class="submit">
      <input type="submit" name="submit" value="<?php _e('Update Options') ?> &raquo;" />
    </div>
  </form>
  <?php
}

//----------------------------------------------------------------------------
//		WORDPRESS FILTERS AND ACTIONS
//----------------------------------------------------------------------------

add_action('admin_menu', 'members_only_add_options_page');
add_action('login_form', 'members_only_login_redirect');

if ($members_only_opt['members_only'] == TRUE) //Check if Members Only is Active
{
  add_action('template_redirect', 'members_only');
  add_action('init', 'members_only_init');
  add_action('show_user_profile', 'members_only_display_feedkey');
  add_action('edit_user_profile', 'members_only_display_feedkey');
  add_action('profile_update', 'members_only_reset_feedkey');
}



/****************** END OF MEMBERS ONLY PLUGIN      *****************/





/********* START OF CODE TO COPY SCHEDULESET VIA AJAX     ******************/
function copy_scheduleset() {

  // The $_REQUEST contains all the data sent via ajax
  if ( isset($_REQUEST['frompostid'])  && isset($_REQUEST['topostid']) ) {

    $frompostid = $_REQUEST['frompostid'];
    $topostid = $_REQUEST['topostid'];

    $frompostcontent = get_post($frompostid);
    // print_r($frompostcontent);




    $serialized_from_post_content =  $frompostcontent->post_content;
    $unserialized_from_post_content = unserialize($frompostcontent->post_content);


    $to_post = get_post($topostid);
    $unserialized_to_post_content = unserialize($to_post->post_content);
    // print_r($unserialized_to_post_content['timings']);
    $unserialized_to_post_content['timings'] = $unserialized_from_post_content['timings'];
    // print_r($unserialized_to_post_content);
    // print_r($unserialized_to_post_content['timings']);




    // echo $unserialized_post_content;
    // echo $topostid;


    // $result = json_encode($unserialized_post_content['timings']);
    $serialized_to_post_content = serialize($unserialized_to_post_content);
    // echo $serialized_to_post_content;
    // echo $topostid;

    $topostarray = array(
      'ID'           => $topostid,
      'post_content' => $serialized_to_post_content,
    );


    $updated_post_id = wp_update_post( $topostarray );

    if ($updated_post_id !== 0){
      echo 'Post updated';
    }
    else{
      echo 'Error';
    }
  }
  else
  echo 'Invalid Inputs';

  die();
}


add_action( 'wp_ajax_copy_scheduleset', 'copy_scheduleset' );



/********* END OF CODE TO COPY SCHEDULESET VIA AJAX     ******************/


/**************   START OF CODE FOR DEFINING ADMIN AJAX URL IN FRONTEND ****************/
add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

  echo '<script type="text/javascript">
  var ajaxurl = "' . admin_url('admin-ajax.php') . '";
  </script>';
}

/**************   END OF CODE FOR DEFINING ADMIN AJAX URL IN FRONTEND ****************/
