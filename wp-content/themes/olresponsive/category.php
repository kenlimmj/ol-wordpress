<?php get_header(); ?>
<div class="container">
  <div class="page-header">
  <h1><?php single_cat_title(); ?></h1>
  </div>
  <?php
      $topics = objectToArray(get_categories('child_of='.$cat));
      $topiccount = count($topics);
  ?>
<div id="lecturelist">
<div class="row">
<div class="span4">
<ul class="nav nav-tabs nav-stacked">
<?php
for ($n=0; $n<$topiccount; $n++) {
  echo '<li><a href="#'.$topics[$n][slug].'" class="topic" data-toggle="tab">'.$topics[$n][name].'</a></li>';
}
?>
</ul>
</div><!--span4-->
<div class="span8">
<div class="tab-content well">
<?php
for ($n=0; $n<$topiccount; $n++) {
  echo '<div class="tab-pane" id="'.$topics[$n][slug].'">';
  echo '<h3>'.category_description($topics[$n][cat_ID]).'</h3>';
  echo '<ul class="nav nav-pills nav-stacked list">';
  foreach (get_posts('cat='.$topics[$n][cat_ID].'&posts_per_page=30&order=ASC&orderby=title') as $post) {
    setup_postdata($post);
    echo '<li><a href="'.get_permalink($post->ID).'" class="lesson">'.get_the_title().'</a></li>';
  }
  echo '</ul>';
  echo '</div><!--tab-pane-->';
}
?>
</div><!--tab-content-->
</div><!--span8-->
</div><!--row-->
</div><!--lecturelist-->
</div><!--container-->
<?php get_footer(); ?>
<script>
var options = {
    valueNames: ['topic','lesson']
};

var featureList = new List('lecturelist', options);
</script>