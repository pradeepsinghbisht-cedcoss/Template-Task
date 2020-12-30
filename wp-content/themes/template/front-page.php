<?php get_header(); ?>

<!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="main-banner header-text">
      <div class="container-fluid">
        <div class="owl-banner owl-carousel">
            <?php $allpost = new WP_Query(array( 'post_type' => 'post', 'post_status' => 'publish' ));
                if ( $allpost->have_posts() ) {
                  while ( $allpost->have_posts() ) {
                      $allpost->the_post(); ?>
                          <div class="item">
                            <?php echo the_post_thumbnail(array(437, 378)); ?>
                            <div class="item-content">
                              <div class="main-content">
                                <div class="meta-category">
                                  <span> <?php the_category('|'); ?></span>
                                </div>
                                <a href=<?php the_permalink(); ?>><h4><?php the_title(); ?></h4></a>
                                <ul class="post-info">
                                  <li><a href="#"><?php the_author(); ?></a></li>
                                  <li><a href="#"><?php the_date(); ?></a></li>
                                  <li><a href="#"><?php comments_number(); ?></a></li>
                                </ul>
                              </div>
                            </div>
                          </div>
            <?php  }
                } else {
                    // no posts found
                }
            ?>
        </div>
      </div>
    </div>
    <!-- Banner Ends Here -->

    <section class="call-to-action">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="main-content">
              <div class="row">
                <div class="col-lg-8">
                  <span>Stand Blog HTML5 Template</span>
                  <h4>Creative HTML Template For Bloggers!</h4>
                </div>
                <div class="col-lg-4">
                  <div class="main-button">
                    <a rel="nofollow" href="https://templatemo.com/tm-551-stand-blog" target="_parent">Download Now!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="blog-posts">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="all-blog-posts">
              <div class="row">
              <?php $allpost = new WP_Query(array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3));
                if ( $allpost->have_posts() ) {
                  while ( $allpost->have_posts() ) {
                    $allpost->the_post(); ?>
                    <div class="col-lg-12">
                      <div class="blog-post">
                        <div class="blog-thumb">
                          <?php echo the_post_thumbnail(array(730, 322)); ?>    
                        </div>
                        <div class="down-content">
                          <span><?php the_category(); ?></span>
                          <a href=<?php the_permalink(); ?>><h4><?php the_title(); ?></h4></a>
                          <ul class="post-info">
                            <li><a href="#"><?php the_author(); ?></a></li>
                            <li><a href="#"><?php the_date(); ?></a></li>
                            <li><a href="#"><?php comments_number(); ?></a></li>
                          </ul>
                          <p><?php the_excerpt(); ?></p>
                          <div class="post-options">
                            <div class="row">
                              <div class="col-6">
                                <ul class="post-tags">
                                  <li><i class="fa fa-tags"></i></li>
                                  <li><a href="#"><?php the_tags(); ?></a></li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="post-share">
                                  <li><i class="fa fa-share-alt"></i></li>
                                  <li><a href="#">Facebook</a>,</li>
                                  <li><a href="#"> Twitter</a></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
            <?php }
                }
              ?>
                <div class="col-lg-12">
                  <div class="main-button">
                    <a href="blog-entries">View All Posts</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="sidebar">
              <div class="row">
                <div class="col-lg-12">
                  <div class="sidebar-item search">
                    <form id="search_form" name="gs" method="GET" action="#">
                      <?php get_sidebar(); ?>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
                      
<?php get_footer(); ?>