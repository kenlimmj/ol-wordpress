<?php
	$topics = objectToArray(get_posts('numberposts=-1'));
	foreach ($topics as $foo => $topic) {
		$topiclist[$foo] = $topic[post_title];
	}
	$search_source = implode('","',$topiclist);
?>
<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<input type="text" data-provide="typeahead" class="span11" data-source='["<?php echo $search_source; ?>","Is Linan a Panda?","Lydia the Cookie Monster"]' placeholder="Get started. Search for a lesson." />
</form>