<?php get_header(); ?>
<body>
<div class="container">
<br/>
<div class="row">
	<div class="span6">
	<?php if ( have_posts() ) : ?>
	<h1>A droid whirrs and beeps and...<br/><small>Was this what you were looking for?</small></h1>
	<ul class="nav nav-pills nav-stacked">
	<?php while ( have_posts() ) : the_post() ?>
	<?php
      $title = the_title('','',false);
      $title_short = substr($title,5);
	?>
	<li><a href="<?php the_permalink(); ?>"><?php echo $title_short ?></a></li>
	<?php endwhile; ?>
	</ul>
	</div><!--span6-->
	<?php else : ?>
		<h1>Bother! <small>These are clearly not the droids you are looking for.</small></h1>
		<p>Can we convince you to jiggle your search terms around for a bit?</p>
		<form method="get" action="<?php echo home_url( '/' ); ?>">
          <input type="text" class="search-query" placeholder="Find a lecture" name="s" id="search" value="<?php the_search_query(); ?>">
        </form>
	</div><!--span6-->
	<?php endif; ?>
	<div class="span6 aligncenter">
		<img src="/wp-content/themes/olresponsive/img/r2d2.png">
	</div><!--span6-->
</div><!--row-->
</div><!--container-->
</body>
<?php get_footer(); ?>