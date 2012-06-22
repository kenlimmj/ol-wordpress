<?php get_header(); ?>
<div class="container padtop20">
	<div class="row-fluid">
		<div class="span2 aligncenter">
		<h1 class="bloat6 line6"><i class="icon-user-md icon-large"></i></h1>
		</div><!--span4-->
		<div class="span10">
<h1 class="bloat4 line2">oh no!</h1>
<h6 class="bloat2 line2">We have a crisis. A matter of life and death.</h6>
</div><!--span8-->
</div><!--row-fluid-->
<div class="padtop40">
<div class="alert alert-error">At <?php echo current_time('mysql'); ?> today, an intergalactic wormhole opened and swallowed the stuff you were looking for. What are your orders, Commander?</div>
</div><!--padtop20-->
<div class="row-fluid">
	<div class="span4 aligncenter">
		<h1 class="bloat4 padtop40">A</h1>
		<h3>Reverse Space Time</h3>
		<hr class="soften">
		<a href="javascript:history.back()"><span>
		<h1 class="bloat4"><i class="icon-arrow-left"></i></h1>
		<p>Go back to the previous page</p>
		</span></a>
		<h2 class="padbot40">or</h2>
		<a href="http://sg.openlectures.org"><span>
		<h1 class="bloat4"><i class="icon-home"></i></h1>
		<p>Start over from the beginning</p>
		</span></a>
	</div><!--span4-->
	<div class="span4 aligncenter">
		<h1 class="bloat4 padtop40">B</h1>
		<h3>Triangulate their Position</h3>
		<hr class="soften">
		<?php get_search_form(); ?>
		<p>Browse by Subject</p>
		<ul class="nav nav-tabs nav-stacked">
			<?php wp_list_categories('depth=1&exclude=200&title_li=&order=desc'); ?>
		</ul>
	</div><!--span4-->
	<div class="span4 aligncenter">
		<h1 class="bloat4 padtop40">C</h1>
		<h3>Fire the Photon Blaster</h3>
		<hr class="soften">
		<p>Let us know if we've messed up.</p>
	</div><!--span4-->
</div><!--row-fluid-->
</div><!--container-->
<?php get_footer(); ?>