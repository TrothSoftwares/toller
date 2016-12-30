<?php
/**
* Template Name: Ring Scheduler

*/

get_header();
?>


<?php


if(isset($_POST['submit'])){
  // print_r($_POST);
  //  exit;
  if(isset($_POST['postid']))
 $updatepostid = $_POST['postid'];
 else $updatepostid = $_POST['exam_postid'];


  $serializedpost =  serialize($_POST);
  $my_post = array(
    'ID'           => $updatepostid,
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
  'orderby'       =>  'title',
  'order'         =>  'ASC',
  'category_name' =>  'regular',
  'posts_per_page' => 10
);

$user_posts = get_posts($args);



$exampostargs = array(
  'author'        =>  $current_user_id,
  'orderby'       =>  'title',
  'order'         =>  'ASC',
  'category_name' =>  'exam',
  'posts_per_page' => 10
);

$exam_posts = get_posts($exampostargs);

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

              <?php
               $post_title = $user_post->post_title;
               $post_id = $user_post->ID;

              $postcontent = $user_post->post_content;

              if($postcontent !== ''){

                $unserialized_postcontent = unserialize($postcontent)   ;

                // print_r($unserialized_postcontent);
              }


              ?>

              <tr>
                <form action="#" method="post">
                  <td> <?php echo $user_post->post_title; ?></td>
                  <td><button type="button" class="btn btn-xs  <?php echo $unserialized_postcontent['assignation']? 'btn-success':'btn-danger'; ?>" style="text-transform:capitalize"><?php echo $unserialized_postcontent['assignation']? $unserialized_postcontent['assignation']:'Un assigned'; ?></button></td>
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




                            <div class="row">
                              <div class="col-md-2">
                                <h6 >Assigned Day :</h6>
                              </div>

                              <div class="col-md-4">
                                <select class="assignation form-control " name="assignation">
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
                              <div class="col-md-6 modalbtns">
                                <button type="submit" class="btn btn-success pull-right" name="submit" value="<?php echo  $user_post->post_title ;?>">Submit</button>
                                <button type="button" class="btn btn-info pull-right" data-toggle="collapse" data-target="#coll_<?php echo  $user_post->post_title ;?>">Copy From..</button>
                                <button type="button" class="btn btn-default pull-right clear_all_button" scheduleset="<?php echo $post_title;?>">Clear All</button>

                              </div>
                            </div>

                            <div class="clearfix">

                            </div>

                            <div id="coll_<?php echo  $user_post->post_title ;?>" class="collapse">

                              <div class="row dupli">
                                <div class="col-md-6 nph">
                                  <label style="float:right;margin-top:5px;" for="sel1">Select the schedule :</label>
                                </div>
                                <div class="col-md-4">
                                  <div class="form-group">


                                    <select class="form-control <?php echo $user_post->ID;?>_copy-select">
                                      <?php foreach($user_posts as $user_post){
                                        if($user_post->post_title !== $post_title)
                                        echo '<option value="'.$user_post->ID.'">'.$user_post->post_title.'</option>';
                                      }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-2">
                                  <button type="button" postid="<?php echo $post_id;?>"  class="copy-select-button btn btn-block btn-success pull-right" >Copy  </button>

                                </div>
                              </div>


                            </div>



                            <div  class="modal fade" role="dialog" id="DupModal_<?php echo  $post_title ;?>">
                              <div class="modal-dialog">
                              </div>
                            </div>
                            <hr>



                            <table class="form-table table-borderless table <?php echo $post_title;?>_table" id="tableid">
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
                                  <tr id="<?php echo $post_title;?>_<?php echo $key;?>">
                                    <td>


                                      <label for="timing[<?php echo $key;?>][time]"><?php echo $key; ?></label>
                                    </td>

                                    <td>
                                      <input type="text" class="timepicker timefield form-control input-sm" value="<?php echo $value['time'];?>" id="time_<?php echo $key;?>" name="timings[<?php echo $key;?>][time]">
                                    </td>

                                    <td>


                                      <select  class="filefield form-control input-sm" name="timings[<?php echo $key;?>][file]" id="file_<?php echo $key;?>">
                                        <option value="" >Select File</option>
                                          <?php foreach ( $query_images->posts as $image ) { ?>




                                            <option value="<?php echo $image->post_title;?>" <?php echo $image->post_title == $value['file'] ? 'selected="selected"' : ''; ?> > <?php echo $image->post_title;?> </option>

                                            <?php } ?>

                                          </select>

                                        </td>
                                        <td>
                                          <button type="button" class="btn btn-default btn-sm removeField" rowid="<?php echo $post_title._.$key;?>"  >Clear</button>

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





                </table>


              </div>







<!-- ************************** start of exam schedule code  -->


              <div class="col-md-6">
                <h3>Exam</h3>
                <table class="table ">
                  <?php foreach ($exam_posts as $exam_post) { ?>

                    <?php
                     $exam_post_title = $exam_post->post_title;
                     $exam_post_id = $exam_post->ID;

                    $exam_postcontent = $exam_post->post_content;



                    if($exam_postcontent !== ''){

                      $exam_unserialized_postcontent = unserialize($exam_postcontent);

                    }


                    ?>

                    <tr>
                      <form action="#" method="post">
                        <td> <?php echo $exam_post_title; ?></td>
                        <td><button type="button" class="btn btn-xs  <?php echo $exam_unserialized_postcontent['assignation']? 'btn-success':'btn-danger'; ?>" style="text-transform:capitalize"><?php echo $exam_unserialized_postcontent['assignation']? $exam_unserialized_postcontent['assignation']:'Un assigned'; ?></button></td>
                        <td>
                          <button class="btn btn-xs  btn-primary" type="button" data-toggle="modal" data-target="#myModal_<?php echo  $exam_post_title ;?>" aria-expanded="false" aria-controls="collapseExample">
                            Edit
                          </button>
                        </td>
                        <td>
                          <input type="hidden" value="<?php echo $exam_post_id; ?>" name="exam_postid">


                          <div  class="modal fade" role="dialog" id="myModal_<?php echo  $exam_post_title ;?>">

                            <div class="modal-dialog">

                              <!-- Modal content-->
                              <div class="modal-content">

                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title"><?php echo $exam_post_title; ?></h4>
                                </div>
                                <div class="modal-body">




                                  <div class="row">
                                    <div class="col-md-2">
                                      <h6 >Assigned Day :</h6>
                                    </div>

                                    <div class="col-md-4">
                                      <select class="assignation form-control " name="assignation">
                                        <option value=''>Select Day</option>
                                        <option value='monday' <?php echo $exam_unserialized_postcontent['assignation'] == 'monday' ? 'selected="selected"' : ''; ?>>Monday</option>
                                        <option value='tuesday' <?php echo $exam_unserialized_postcontent['assignation'] == 'tuesday' ? 'selected="selected"' : ''; ?> >Tuesday</option>
                                        <option value='wednesday' <?php echo $exam_unserialized_postcontent['assignation'] == 'wednesday' ? 'selected="selected"' : ''; ?>>Wednesday</option>
                                        <option value='thursday' <?php echo $exam_unserialized_postcontent['assignation'] == 'thursday' ? 'selected="selected"' : ''; ?>>Thursday</option>
                                        <option value='friday' <?php echo $exam_unserialized_postcontent['assignation'] == 'friday' ? 'selected="selected"' : ''; ?>>Friday</option>
                                        <option value='saturday' <?php echo $exam_unserialized_postcontent['assignation'] == 'saturday' ? 'selected="selected"' : ''; ?>>Saturday</option>
                                        <option value='sunday' <?php echo $exam_unserialized_postcontent['assignation'] == 'sunday' ? 'selected="selected"' : ''; ?>>Sunday</option>
                                      </select>
                                    </div>
                                    <div class="col-md-6 modalbtns">
                                      <button type="submit" class="btn btn-success pull-right" name="submit" value="<?php echo  $exam_post_title ;?>">Submit</button>
                                      <button type="button" class="btn btn-info pull-right" data-toggle="collapse" data-target="#coll_<?php echo  $exam_post_title ;?>">Copy From..</button>
                                      <button type="button" class="btn btn-default pull-right clear_all_button" scheduleset="<?php echo $exam_post_title;?>">Clear All</button>

                                    </div>
                                  </div>

                                  <div class="clearfix">

                                  </div>

                                  <div id="coll_<?php echo  $exam_post_title ;?>" class="collapse">

                                    <div class="row dupli">
                                      <div class="col-md-6 nph">
                                        <label style="float:right;margin-top:5px;" for="sel1">Select the schedule :</label>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group">


                                          <select class="form-control <?php echo $exam_post_id;?>_copy-select">
                                            <?php foreach($exam_posts as $exam_post){
                                              if($exam_post->post_title !== $exam_post_title)
                                              echo '<option value="'.$exam_post->ID.'">'.$exam_post->post_title.'</option>';
                                            }
                                            ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <button type="button" postid="<?php echo $exam_post_id;?>"  class="copy-select-button btn btn-block btn-success pull-right" >Copy  </button>

                                      </div>
                                    </div>


                                  </div>



                                  <div  class="modal fade" role="dialog" id="DupModal_<?php echo  $exam_post_title ;?>">
                                    <div class="modal-dialog">
                                    </div>
                                  </div>
                                  <hr>



                                  <table class="form-table table-borderless table <?php echo $exam_post_title;?>_table" id="tableid">
                                    <thead>
                                      <td>Sl.No </td>
                                      <td>Time </td>
                                      <td>Ring Tone</td>
                                      <td>Clear </td>

                                    </thead>
                                    <tbody>

                                      <?php


                                      foreach ($exam_unserialized_postcontent['timings'] as $key => $value) {

                                        ?>
                                        <tr id="<?php echo $exam_post_title;?>_<?php echo $key;?>">
                                          <td>


                                            <label for="timing[<?php echo $key;?>][time]"><?php echo $key; ?></label>
                                          </td>

                                          <td>
                                            <input type="text" class="timepicker timefield form-control input-sm" value="<?php echo $value['time'];?>" id="time_<?php echo $key;?>" name="timings[<?php echo $key;?>][time]">
                                          </td>

                                          <td>

                                            <select  class="filefield form-control input-sm" name="timings[<?php echo $key;?>][file]" id="file_<?php echo $key;?>">
                                              <option value="" >Select File</option>
                                                <?php foreach ( $query_images->posts as $image ) { ?>

                                                  <option value="<?php echo $image->post_title;?>" <?php echo $image->post_title == $value['file'] ? 'selected="selected"' : ''; ?> > <?php echo $image->post_title;?> </option>

                                                  <?php } ?>

                                                </select>

                                              </td>
                                              <td>
                                                <button type="button" class="btn btn-default btn-sm removeField" rowid="<?php echo $exam_post_title._.$key;?>"  >Clear</button>

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





                      </table>


                    </div>




                    <!-- ************************** end of exam schedule code  -->

            </div>
          </div>


        </main>
      </div>
      <?php get_sidebar('right'); ?>




      <?php get_footer(); ?>

      <script type="text/javascript">

      jQuery(document).ready(function(){



        jQuery('.clear_all_button').click(function(){
          var schedulesetid = jQuery(this).attr('scheduleset');
          jQuery('.'+schedulesetid+'_table').find('.timefield').removeAttr('value');
          jQuery('.'+schedulesetid+'_table').find(".filefield").prop('selectedIndex',0)
        });



        jQuery('.removeField').click(function(){

           var rowid = jQuery(this).attr('rowid');
          // console.log(jQuery("#"+rowid).find('.timefield'));


          jQuery("#"+rowid).find(".timefield").removeAttr('value');

          jQuery("#"+rowid).find(".filefield").prop('selectedIndex',0)

          // jQuery('#row_'+rowid).remove();


          // jQuery('#tableid').append(input);
        })



        jQuery('.copy-select-button').click(function(){


          var topostid = jQuery(this).attr('postid');
          // console.log(topostid);
          var frompostid =  jQuery('.'+topostid + '_copy-select').val();
          // console.log(frompostid);


          // This does the ajax request
          jQuery.ajax({
            url: ajaxurl,
            data: {
              'action':'copy_scheduleset',
              'frompostid' :frompostid,
              'topostid' : topostid,
            },
            beforeSend: function() {
            jQuery('.copy-select-button').html('Copying...');
            },





            success:function(data) {
              jQuery('.copy-select-button').html('Copied!');
              // This outputs the result of the ajax request
              window.location = 'http://localhost/toller/?page_id=22';



            },
            error: function(errorThrown){
              jQuery('.copy-select-button').html('Something went wrong!');
              window.location = 'http://localhost/toller/?page_id=22';
            }
          });

        });






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
