<?php
/**
 * Template Name: Ring Scheduler

 */

 get_header();
 ?>


 <?php


 if(isset($_POST['submit'])){
   print_r($_POST);
  //  exit;

    $serializedpost =  serialize($_POST);
    $my_post = array(
        'ID'           => $_POST['postid'],
        'post_content' => $serializedpost,
    );

  // Update the post into the database
    wp_update_post( $my_post );
   //  $serializedpost =  serialize($_POST);

 }



 /**
  * determine main column size from actived sidebar
  */
 $main_column_size = bootstrapBasicGetMainColumnSize();
 ?>
 <?php get_sidebar('left');

$current_user_id = get_current_user_id();

$query_images_args = array(
  'post_type'      => 'attachment',
  'post_status' => 'inherit',
  'post_mime_type' => 'audio',
  'posts_per_page' => - 10,
  'author' => $current_user_id
);

$query_images = new WP_Query( $query_images_args );


 $args = array(
   'author'        =>  $current_user_id,
   'orderby'       =>  'post_date',
   'order'         =>  'ASC',
   'posts_per_page' => 10
 );

 $user_posts = get_posts($args);

 // print_r($user_posts);

 ?>



 				<div class="col-md-<?php echo $main_column_size; ?> content-area" id="main-column">
 					<main id="main" class="site-main" role="main">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <h3>Regular</h3>
                <table class="table ">
                  <?php foreach ($user_posts as $user_post) { ?>

                    <tr>
                      <form action="#" method="post">
                      <td>
                        <?php echo $user_post->post_title; ?></td>
                       <td><button type="button" class="btn btn-xs  btn-success">Monday</button></td>
                      <td>
                      <button class="btn btn-xs  btn-primary" type="button" data-toggle="modal" data-target="#myModal_<?php echo  $user_post->post_title ;?>" aria-expanded="false" aria-controls="collapseExample">
                          Edit
                      </button>
                    </td>
                    <td>
                      <input type="hidden" value="<?php echo $user_post->ID; ?>" name="postid">


                      <div  class="modal fade" role="dialog" id="myModal_<?php echo  $user_post->post_title ;?>">
                        <div class="modal-dialog">

   <!-- Modal content-->
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h4 class="modal-title"><?php echo $user_post->post_title; ?></h4>
     </div>
     <div class="modal-body">


<?php   $postcontent = $user_post->post_content;

if($postcontent !== ''){
$unserialized_postcontent = unserialize($postcontent)   ;
}

?>


  <div class="col-md-2">
    <h6 >Assigned Day :</h6>
  </div>

  <div class="col-md-4">
    <select class="assignation form-control" name="assignation">
      <option value=''>Select Day</option>
      <option value='monday' <?php echo $unserialized_postcontent['assignation'] == 'monday' ? 'selected="selected"' : ''; ?>>Monday</option>
      <option value='tuesday' <?php echo $unserialized_postcontent['assignation'] == 'tuesday' ? 'selected="selected"' : ''; ?> >Tuesday</option>
      <option value='wednesday' <?php echo $unserialized_postcontent['assignation'] == 'wednesday' ? 'selected="selected"' : ''; ?>>Wednesday</option>
      <option value='thursday' <?php echo $unserialized_postcontent['assignation'] == 'thursday' ? 'selected="selected"' : ''; ?>>Thursday</option>
      <option value='friday' <?php echo $unserialized_postcontent['assignation'] == 'friday' ? 'selected="selected"' : ''; ?>>Friday</option>
      <option value='saturday' <?php echo $unserialized_postcontent['assignation'] == 'saturday' ? 'selected="selected"' : ''; ?>>Saturday</option>
      <option value='sunday' <?php echo $unserialized_postcontent['assignation'] == 'sunday' ? 'selected="selected"' : ''; ?>>Sunday</option>
    </select>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-success pull-right" name="submit" value="<?php echo  $user_post->post_title ;?>">Submit</button>

  </div>
  <hr>



         <table class="form-table table-borderless table" id="tableid">
           <thead>
             <td>Sl.No </td>
             <td>Time </td>
             <td>Ring Tone</td>
             <td>Clear </td>

           </thead>
           <tbody>

             <?php


             foreach ($unserialized_postcontent['timings'] as $key => $value) {
               ?>
               <tr id="row_<?php echo $key;?>">
                 <td>
                   <label for="timing[<?php echo $key;?>][time]"><?php echo $key; ?></label>
                 </td>

                 <td>
                   <input type="text" class="timepicker timefield form-control input-sm " value="<?php echo $value['time'];?>" id="time_<?php echo $key;?>" name="timings[<?php echo $key;?>][time]">
                 </td>

                 <td>

                   <select  class="filefield form-control input-sm" name="timings[<?php echo $key;?>][file]" id="file_<?php echo $key;?>">
                       <option value="" >Select File</option
                     <?php foreach ( $query_images->posts as $image ) { ?>

                       <option value="<?php echo $image->post_title;?>" <?php echo $image->post_title == $value['file'] ? 'selected="selected"' : ''; ?> > <?php echo $image->post_title;?> </option>

                       <?php } ?>

                     </select>

                   </td>
                   <td>
                     <button type="button" class="btn btn-default btn-sm" rowid="<?php echo $key;?>" class="removeField" >Clear</button>

                   </td>

                 </tr>
               <?php
             }




               ?>


               </tbody>
             </table>


     </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     </div>
   </div>

 </div>

                      </div>
                    </td>





</form>
                  </tr>
                    <?php }?>



                    <tr><td>Schedule Set 2</td> <td><button type="button" class="btn btn-xs  btn-danger">Not Assigned</button></td></tr>
                    <tr><td>Schedule Set 4</td><td><button type="button" class="btn btn-xs  btn-success">Tuesday</button></td></tr>
                    <tr><td>Schedule Set 3</td> <td><button type="button" class="btn btn-xs  btn-success">Wednessday</button></td></tr>
                    <tr><td>Schedule Set 5</td> <td><button type="button" class="btn btn-xs  btn-danger">Not Assigned</button></td></tr>
                    <tr><td>Schedule Set 6</td> <td><button type="button" class="btn btn-xs  btn-danger">Not Assigned </button></td></tr>
                    <tr><td>Schedule Set 7</td> <td><button type="button" class="btn btn-xs  btn-success">Saturday</button></td></tr>

                </table>


              </div>

              <div class="col-md-6">
                <h3>Exam </h3>
                <table  class="table ">
                  <tr>
                    <tr><td>Schedule Set 1</td> <td><button type="button" class="btn btn-xs  btn-success">Monday</button></td></tr>
                    <tr><td>Schedule Set 2</td> <td><button type="button" class="btn btn-xs  btn-danger">Not Assigned</button></td></tr>

                  </tr>
                </table>

            </div>

            </div>
          </div>


 					</main>
 				</div>
 <?php get_sidebar('right'); ?>



 <script type="text/javascript">

 jQuery(document).ready(function(){
   console.log("sdfsdf");
   jQuery('.timepicker').timepicker({
 timeFormat: 'h:mm p',
 interval: 1,
 minTime: '10',
 maxTime: '6:00pm',
 startTime: '08:00',
 dynamic: false,
 dropdown: true,
 scrollbar: true,
 zindex:1051
 });
 });




 </script>


  <?php get_footer(); ?>