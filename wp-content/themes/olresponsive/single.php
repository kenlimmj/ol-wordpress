<?php
  $post = $wp_query->post;

  if (in_category('233')) {
      include(TEMPLATEPATH.'/blog.php');
  } else {
      include(TEMPLATEPATH.'/lecture.php');
  }
?>