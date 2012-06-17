<?php get_header(); ?>
  <div class="container pushdown">
        <?php if ( have_posts() ) : the_post(); ?>
<?php
  $youtube = get_the_content();
  preg_match_all("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $youtube, $youtube_ID, PREG_PATTERN_ORDER);
  for ($k=0; $k<count($youtube_ID[0]); $k++) {
  $link_ID=rtrim($youtube_ID[0][$k]);
  $foo=get_youtube($link_ID);
  $title[$k]=ucwords($foo['entry']['title']['$t']);
  $description[$k]=$foo['entry']['media$group']['media$description']['$t'];
  $link[$k]="http://www.youtube.com/watch?v=".$link_ID;
  }
  $q=count($youtube_ID[0]);
  $topic_ID = get_the_category();
  $subject_ID = pa_category_top_parent_id($topic_ID[0]->cat_ID);
  $prevpost=get_previous_post(true);
  $nextpost=get_next_post(true);
  if ($prevpost==NULL) {
    $previous_status="disabled";
  }
  elseif ($nextpost==NULL) {
    $next_status="disabled";
  }
?>
<h1><?php echo ucwords(the_title('','',false)); ?> <small>by <?php echo get_the_author(); ?></small></h1>
<div class="progress">
<div class="bar" style="width:<?php echo 1/$q*100; ?>%;"></div>
</div><!--progress-->
<div class="row">
<div class="span4">
    <div class="accordion" id="lecturelist">
    <?php
      for ($k=0; $k<$q; $k++) {
      echo '<div class="accordion-group">';
      echo '<div class="accordion-heading">';
      echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#lecturelist" href="#'.$k.'" onclick="jQuery(\'.bar\').width(\''.(($k+1)/$q*100).'%\'); jwplayer().load(\''.$link[$k].'\');">'.($k+1).'. '.$title[$k].'</a>';
      echo '</div>';
      echo '<div id="'.$k.'" class="accordion-body collapse">';
      echo '<div class="accordion-inner">';
      echo $description[$k];
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    ?>
    </div>
<ul class="pager">
<li class="previous <?php echo $previous_status; ?>">
<a href="<?php echo get_permalink($prevpost->ID); ?>"><i class="icon-chevron-left"></i> Previous Lesson</a>
</li>
<li class="next <?php echo $next_status; ?>">
<a href="<?php echo get_permalink($nextpost->ID); ?>">Next Lesson <i class="icon-chevron-right"></i></a>
</li>
</ul>
</div>
<div class="span8">
  <div class="video cbar-none">
        <video preload src="<?php echo $link[0]; ?>" id="container" poster="/wp-content/themes/olresponsive/img/poster.png"/></video>
  </div><!--video-->
  <hr>
</div>
</div>
<?php endif; ?>
</div><!--container-->
<?php get_footer(); ?>