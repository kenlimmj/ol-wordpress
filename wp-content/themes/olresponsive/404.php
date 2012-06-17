<?php get_header(); ?>
<div class="container" style="padding-top:40px;">
	<h1 class="fuckingbig">Stop!</h1>
	<h6 style="font-size:200%; padding-top:30px; padding-bottom:10px;">Stop! Or we'll shoot!</h6>
	<div class="alert alert-error">What you seek does not exist. Go no further. You have three choices ahead of you. Choose wisely.</div>
<hr>
<div class="row">
	<div class="span4 aligncenter">
			<h1 style="font-size:400%;">A</h1>
			<h3>Return</h3>
			<hr>
			<a href="javascript:history.back()"><span>
			<h1 style="font-size:400%;"><i class="icon-arrow-left"></i></h1>
			<span>
			<p class="medium">Go back to the previous page</p>
			</span></a>
			<h2>or</h2>
			<a href=<?php bloginfo('url'); ?>><span>
			<h1 style="font-size:400%;"><i class="icon-home"></i></h1>
			<p class="medium">Start over from the beginning</p>
			</span></a>
	</div><!--1/3col-->
	<div class="span4 aligncenter">
		<h1 style="font-size:400%;">B</h1>
		<h3>Explore</h3>
		<hr>
		<p class="medium">Choose a subject. Any subject.</p>
		<ul class="nav nav-pills nav-stacked">
			<?php wp_list_categories('depth=1&exclude=200&title_li=&order=desc'); ?>
		</ul>
	</div><!--1/3col-->
	<div class="span4 aligncenter">
		<h1 style="font-size:400%;">C</h1>
		<h3>Fight!</h3>
		<hr>
		<p>There's a chance we might have gotten something wrong. Let us know?</p>
		<?php echo do_shortcode('[gravityform id="2" name="Feedback" title="false" description="false" ajax="true"]'); ?>
	</div><!--1/3col-->
</div><!--row-->
<?php get_footer(); ?>