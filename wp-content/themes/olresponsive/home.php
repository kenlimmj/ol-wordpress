<?php get_header(); ?>
<body>
<div class="container">
	<div class="aligncenter">
	<div class="monitor black">
		<div class="glow-m"></div>
		<div class="screen-m">
			<div class="video-container">
			</div>
		</div>
		<div class="base-m"></div>
	    <div class="top-base-shadow"></div>
		<div class="stand-m"></div>
	</div><!--monitor-->
	</div><!--aligncenter-->
<div class="row-fluid">
	<div class="span8">
		<h1><span class="openlectures"><strong>open</strong>lectures</span></h1>
	<?php get_search_form(); ?>
	<div class="btn-group">
	<a class="btn btn-large" href="/everything"><i class="icon-reorder"></i> Browse Everything</a>
    <button class="btn dropdown-toggle btn-large" data-toggle="dropdown" href="#">
    <i class="icon-sitemap"></i> By Subject <i class="icon-caret-down"></i>
    </button>
    <ul class="dropdown-menu">
	    <li><a href="/economics">Economics</a></li>
	    <li><a href="/chemistry">Chemistry</a></li>
	    <li><a href="/physics">Physics</a></li>
	    <li><a href="/mathematics">Mathematics</a></li>
	    <li><a href="/biology">Biology</a></li>
	    <li><a href="/literature">Literature</a></li>
	    <li><a href="/gp">General Paper</a></li>
	    <li><a href="/music">Music</a></li>
	    <li><a href="/history">History</a></li>
    </ul>
    </div>
</div><!--span8-->
<div class="span4">
	<p class="lead">Free online lectures for anyone who wants to learn - because we believe that effort, and not money, should decide success.</p>
</div><!--span4-->
</div><!--row-fluid-->
</div><!--container-->
<!--[if lt IE 7 ]>
  <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
  <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
<![endif]-->
</body>
<?php get_footer(); ?>