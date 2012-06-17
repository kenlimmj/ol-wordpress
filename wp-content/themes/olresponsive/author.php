<?php get_header(); ?>

<div class="container">
	<div class="row">

<?php
$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
$description = $curauth->description;
$designation_list = get_cimyFieldValue($curauth->ID, 'DESIGNATION');
$designations = explode(",",$designation_list);
$subject_list = get_cimyFieldValue($curauth->ID, 'SUBJECT');
$subjects = explode(",",$subject_list);
$gender = get_cimyFieldValue($curauth->ID, 'GENDER');
$school = get_cimyFieldValue($curauth->ID, 'SCHOOL');
$gradyear = get_cimyFieldValue($curauth->ID, 'GRAD');
$university = get_cimyFieldValue($curauth->ID, 'UNIVERSITY');
$major = get_cimyFieldValue($curauth->ID, 'MAJOR');
$video = get_cimyFieldValue($curauth->ID, 'TEASER');
$cv = get_cimyFieldValue($curauth->ID, 'CV');

if ($gender=="Male") {
	$ns=2;
}
else {
	$ns=0;
}

if (count($designations)==1) {
	if ($designations[0]=='Lecturer') {
		$primary_designation = 'Lecturer';
	}
	else {
		$primary_designation = 'Staff';
	}
}
else {
	$primary_designation = 'Staff';
	$secondary_designation = ' and Lecturer';
}
if ($primary_designation=='Lecturer' || $secondary_designation==' and Lecturer') {
$subject_count = count($subjects);
for ($i=0; $i<$subject_count; $i++) {
	$subject_label .= '<span class="label"><i class="icon-tag"></i> '.$subjects[$i].'</span> ';
}
}
?>
<div class="span2">
<a class="btn btn-large btn-wide" href="http://openlectures.sg/about-us"><i class="icon-arrow-left"></i> Go Back</a>
<a class="btn btn-large btn-wide" href="mailto:<?php echo $curauth->user_email; ?>"><i class="icon-envelope"></i> Email</a>
<?php
if ($video) {
	echo '<a class="btn btn-large btn-wide" data-toggle="modal" href="#myModal"><i class="icon-facetime-video"></i> Watch Video</a>';
}
if ($cv) {
	echo '<a class="btn btn-large btn-wide" href="'.$cv.'"><i class="icon-download"></i> Download</a>';
}
?>
</div><!--span2-->
<div class="span2">
		<ul class="thumbnails">
		<li class="span2" style="margin-top:10px; margin-bottom:5px;">
			<div class="thumbnail">
			<?php userphoto($curauth->ID,'','','','/wp-content/themes/olresponsive/img/nophoto.png'); ?>
			</div>
		</li>
	</ul>
</div>
<div class="span8">
<div class="page-header">
	<h1><?php echo $curauth->display_name; ?><br/>
	<small><?php echo $primary_designation.$secondary_designation; ?></small></h1>
	<?php echo $subject_label; ?>
</div><!--page-header-->
<?php
if ($description) {
	echo '<em>'.$description.'</em>';
	echo '<hr>';
}
if ($university) {
	echo '<h2>'.$university.' <small><span class="pull-right">Class of '.($gradyear+$ns+5).'</span></small></h2>';
	// echo '<h3>'.$major.'</h3>';
}
if ($school && $gradyear) {
	echo '<h2>'.$school.' <small><span class="pull-right">Class of '.$gradyear.'</span></small></h2>';
}
?>
<hr>
<?php
query_posts($query_string.'&post_status=publish&cat=-200&posts_per_page=-1');
if (have_posts()) {
echo '<h2>Lectures</h2>';
echo '<div class="well lectures">';
}
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<ul class="unstyled">
<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
</ul>
<?php endwhile; else:
endif;
?>
</div><!--well-->
</div><!--span10-->
</div><!--row-->
<div class="modal hide fade" id="myModal">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3><?php echo $curauth->display_name;?> says Hi!</h3>
</div>
<div class="modal-body">
<div class="video cbar-none">
	<video src="<?php echo $video; ?>" id="container" width="100%" height="100%"/></video>
</div>
<script type="text/javascript">
jwplayer('container').setup({
	'flashplayer': '/wp-content/themes/olresponsive/jwplayer/player.swf',
	'width': '100%',
	'height': '100%',
	'autostart': 'true',
	'plugins': {
		'hd-2': {'state': 'true'},
		// 'captions-2': {file:''},
	},
	'controlbar': 'none',
	'logo': {
		'file': '/wp-content/uploads/Logo-Player.png',
		'position': 'top-left',
	},
	'skin': '/wp-content/themes/olresponsive/jwplayer/schoon.zip'
});
</script>
</div>
</div>
</div><!--container-->
<?php get_footer(); ?>