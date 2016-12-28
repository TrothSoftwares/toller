<div class="wrap">
  <h2> Scheduler </h2>
  <?php
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

  ?>



  <!-- Create a header in the default WordPress 'wrap' container -->
  <div class="wrap">


    <?php
    if( isset( $_GET[ 'tab' ] ) ) {
      $active_tab = $_GET[ 'tab' ];
    } // end if
    ?>






    <h2>
      <?php
      foreach ($user_posts as $user_post) { ?>

        <a href="?page=Toller&tab=<?php echo $user_post->post_title;?>" class="nav-tab <?php echo $active_tab == $user_post->post_title ? 'nav-tab-active' : ''; ?>"><?php echo $user_post->post_title ?></a>

        <?php } ?>
      </h2>

      <?php
      foreach ($user_posts as $user_post) { ?>

        <form action="admin.php?page=Toller&tab=<?php echo $user_post->post_title;?>"  method="post" class="<?php echo $active_tab == $user_post->post_title ? '' : 'hidden'; ?>">

<br><br><br>
           <?php
          $postcontent = $user_post->post_content;

          if($postcontent !== ''){

         $unserialized_postcontent = unserialize($postcontent);
       }
         ?>

<select class="assignation" name="assignation">
  <option value=''>Select Day</option>
  <option value='monday' <?php echo $unserialized_postcontent['assignation'] == 'monday' ? 'selected="selected"' : ''; ?>>Monday</option>
  <option value='tuesday' <?php echo $unserialized_postcontent['assignation'] == 'tuesday' ? 'selected="selected"' : ''; ?> >Tuesday</option>
  <option value='wednesday' <?php echo $unserialized_postcontent['assignation'] == 'wednesday' ? 'selected="selected"' : ''; ?>>Wednesday</option>
  <option value='thursday' <?php echo $unserialized_postcontent['assignation'] == 'thursday' ? 'selected="selected"' : ''; ?>>Thursday</option>
  <option value='friday' <?php echo $unserialized_postcontent['assignation'] == 'friday' ? 'selected="selected"' : ''; ?>>Friday</option>
  <option value='saturday' <?php echo $unserialized_postcontent['assignation'] == 'saturday' ? 'selected="selected"' : ''; ?>>Saturday</option>
  <option value='sunday' <?php echo $unserialized_postcontent['assignation'] == 'sunday' ? 'selected="selected"' : ''; ?>>Sunday</option>
</select>

<button type="button" class="clearassignation">Clear Assignation</button>

          <table class="form-table" id="tableid">
            <tbody>

              <?php


              foreach ($unserialized_postcontent['timings'] as $key => $value) {
                ?>
                <tr id="row_<?php echo $key;?>">
                  <td>
                    <label for="timing[<?php echo $key;?>][time]"><?php echo $key; ?></label>
                  </td>

                  <td>
                    <input type="text" class="timepicker timefield" value="<?php echo $value['time'];?>" id="time_<?php echo $key;?>" name="timings[<?php echo $key;?>][time]">
                  </td>

                  <td>

                    <select  class="filefield" name="timings[<?php echo $key;?>][file]" id="file_<?php echo $key;?>">
                        <option value="" >Select File</option
                      <?php foreach ( $query_images->posts as $image ) { ?>

                        <option value="<?php echo $image->post_title;?>" <?php echo $image->post_title == $value['file'] ? 'selected="selected"' : ''; ?> > <?php echo $image->post_title;?> </option>

                        <?php } ?>

                      </select>

                    </td>
                    <td>
                      <button type="button" rowid="<?php echo $key;?>" class="removeField" >Clear</button>

                    </td>

                  </tr>
                <?php
              }




                ?>


                </tbody>
              </table>


              <input type="hidden" name="postid" value="<?php echo $user_post->ID;?>">

              <p class="submit"><input type="submit" name="<?php echo $user_post->post_title;?>" value="Save Changes" class="button-primary" ></p>
            </form>


            <?php }?>


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
    scrollbar: true
});
            });


            jQuery('#addField').click(function(){
              rowlength = jQuery('#rowlength').val();
              rowlengthplus = rowlength +1

              jQuery('#tableid').append(input);
            })

            jQuery('.removeField').click(function(){
               console.log("sdfsdfsdf");
              var rowid = jQuery(this).attr('rowid');


              jQuery("#row_"+rowid).find(".timefield").removeAttr('value');

              jQuery("#row_"+rowid).find(".filefield").prop('selectedIndex',0)
              // jQuery('#row_'+rowid).remove();


              // jQuery('#tableid').append(input);
            })


            jQuery('.clearassignation').click(function(){
              jQuery(".assignation").prop('selectedIndex',0)

            })

            </script>


          </div><!-- /.wrap -->
        </div>
