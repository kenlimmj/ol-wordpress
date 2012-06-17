<?php get_header(); ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=238184899560410";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

  <div class="container">
  <?php if ( have_posts() ) : the_post(); ?>
  <div class="row">
  <div class="span8 offset2 aligncenter">
  <div class="page-header">
    <h1><?php echo the_title(); ?><br/><small><?php the_date('l, F j, Y'); ?></small></h1>
  </div>
  </div><!--span8-->
  </div><!--row-->
  <?php
    $subject = get_the_category();
    $subject_ID = $subject[0]->cat_ID;
    $parentcatID = pa_category_top_parent_id($subject_ID);
    $post_ID = $post->ID;
    $post_list = objectToArray(get_posts('cat='.$subject_ID.'&post_status=publish&orderby=title&order=ASC&posts_per_page=-1'));
    $post_count = count($post_list);
    for ($q=0; $q<$post_count; $q++) {
    $post_list_by_ID[$q] = $post_list[$q][ID];
    }
    $lecture = array_search($post_ID,$post_list_by_ID);
    $previous_post_ID = $post_list_by_ID[$lecture-1];
    $next_post_ID = $post_list_by_ID[$lecture+1];
  ?>
  <div class="row">
  <div class="span2 aligncenter menu-icon">
  <?php
    if ($previous_post_ID==NULL) {
      echo '<i class="icon-chevron-left icon-large muted"></i>';
    }
    else {
      echo '<a href="'.get_permalink($previous_post_ID).'"><i class="icon-chevron-left icon-large"></i></a>';
    }
  ?>
  </div><!--span2-->
  <div class="span8">
  <?php
  if (get_the_content()) {
      the_content();
  } ?>
  </div><!--span8-->
  <div class="span2 aligncenter menu-icon">
  <?php
    if ($next_post_ID==NULL) {
      echo '<i class="icon-chevron-right icon-large muted"></i>';
    }
    else {
      echo '<a href="'.get_permalink($next_post_ID).'"><i class="icon-chevron-right icon-large"></i></a>';
    }
  ?>
  </div><!--span2-->
  </div><!--row-->
  <div class="row">
  <div class="span8 offset2">
  <hr>
  <fb:like send="true" width="770" show_faces="false"></fb:like>
  <fb:comments href="<?php echo get_permalink(); ?>" width="770"></fb:comments>
  </div><!--span8-->
  </div><!--row-->
  <?php endif; ?>
  </div><!--container-->
<?php get_footer(); ?>