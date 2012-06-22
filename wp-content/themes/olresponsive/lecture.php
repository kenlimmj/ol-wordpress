<?php get_header(); ?>
  <div class="container pushdown">
<?php if ( have_posts() ) : the_post(); ?>
<?php
  $spreadsheet = csvToArray(get_the_content());
  $q=count($spreadsheet);
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
      echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#lecturelist" href="#'.$k.'" onclick="jQuery(\'.bar\').width(\''.(($k+1)/$q*100).'%\'); jwplayer().load(\''.$spreadsheet[$k][URL].'\');">'.($k+1).'. '.$spreadsheet[$k][Checkpoint].'</a>';
      echo '</div>';
      echo '<div id="'.$k.'" class="accordion-body collapse">';
      echo '<div class="accordion-inner">';
      echo $spreadsheet[$k][Description];
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
        <video preload src="<?php echo $spreadsheet[0][URL]; ?>" id="container" poster="/wp-content/themes/olresponsive/img/poster.png"/></video>
  </div><!--video-->
</div>
</div>
<?php endif; ?>
</div><!--container-->
<?php get_footer(); ?>