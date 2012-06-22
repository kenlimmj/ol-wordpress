<?php
	if (is_404()) {
		$size="input-xlarge";
	}
	else {
		$size="input-xxlarge";
	}
	$topics = objectToArray(get_posts('numberposts=-1'));
	foreach ($topics as $foo => $topic) {
		$topiclist[$foo] = "{'name':'$topic[post_title]','link':'$topic[guid]'}";
	}
	$search_source = implode(',',$topiclist);
?>
<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<i class="icon-search icon-large"></i> <input type="text" data-provide="typeahead" name="s" id="search" class=<?php echo $size; ?> placeholder="Get started. Search for a lesson." />
</form>
<script>
function displayResult(item, val, text) {
    window.location.href = val;
}
</script>
<?php
echo '<script>';
echo 'jQuery("#search").typeahead({';
echo 'source: ['.$search_source.'],';
echo 'display: "name",';
echo 'val: "link",';
echo 'itemSelected: displayResult';
echo '});';
echo '</script>';
?>