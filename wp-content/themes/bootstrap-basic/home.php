<?php
/**
 * Template Name: Home Page

 */

 get_header();

 /**
  * determine main column size from actived sidebar
  */
 $main_column_size = bootstrapBasicGetMainColumnSize();
 ?>
 <?php get_sidebar('left'); ?>
 				<div class="col-md-<?php echo $main_column_size; ?> content-area" id="main-column">
 					<main id="main" class="site-main" role="main">
            <div class="row">
          		<div class="col-md-12">
          			<div class="row">
          				<div class="col-md-12">
                    <div class="appwrap">
                      <div class="status online">
                        ONLINE
                      </div>
                      <div class="tapp">

                      </div>
                    </div>
                    <div class="cpanel">
                        <a href="http://localhost/toller/?page_id=22">
                        <button class="btn btn-default" type="button">
                          <em class="glyphicon glyphicon-align-left"></em> Ring Schedule
                        </button>
                      </a>
                        <button class="btn btn-default" type="button">
                          <em class="glyphicon glyphicon-user"></em> Speak Now
                        </button>
                        <!-- <button class="btn btn-default pull-right" type="button">
                          <em class="glyphicon glyphicon-folder-open"></em>
                        </button> -->
                        <?php echo do_shortcode('[the_dramatist_front_upload]'); ?>
                        <button class="btn btn-default pull-right" type="button">
                          <em class="glyphicon glyphicon-list-alt"></em> Device log
                        </button>
                    </div>

          				</div>
          			</div>
          		</div>
          	</div>
 					</main>
 				</div>
 <?php get_sidebar('right'); ?>
 <?php get_footer(); ?>
