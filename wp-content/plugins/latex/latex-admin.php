<?php 

define("LaTex_ADMIN", true); 


if (!is_dir(get_option("latex_cache_path"))) 
	mkdir(get_option("latex_cache_path"));
?>


<div class='wrap'>
	<h2><?php _e('LaTeX for WordPress Options'); ?></h2>
<?php

if (isset($_REQUEST["update_latex_option"])){
	update_option("latex_imgcss", $_REQUEST["latex_imgcss"]);
	update_option("latex_mathjax_config", $_REQUEST["latex_mathjax_config"]);
	if ($_REQUEST["latex_img_server"] != "custom")
		update_option("latex_img_server", $_REQUEST["latex_img_server"]);
	else
		update_option("latex_img_server", $_REQUEST["latex_img_custom_server"]);
	if ($_REQUEST["mathjax_server"] != "custom")
		update_option("mathjax_server", $_REQUEST["mathjax_server"]);
	else
		update_option("mathjax_server", $_REQUEST["mathjax_custom_server"]);	
	echo '<div id="message" class="updated fade"><p>Options are updated.</p></div>';				
}elseif (isset($_REQUEST["delete_cache"])) {
	$mask = get_option("latex_cache_path")."*.*";
	array_map( "unlink", glob( $mask ) );
	echo '<div id="message" class="updated fade"><p>LaTex images caches are deleted.</p></div>';
}
?>
	<form method="post">
	<h3>LaTex Image server</h3>
	<p>The LaTex image service is to convert the latex codes in your bolg to images which 
	will be replaced in your posts.	You can use mimetex to build up your own latex service, or just use public ones.
	When the service you chosen is broken, you can just switch one.
	</p>
	<?php 
	$latex_img_servers = array("http://l.wordpress.com/latex.php?bg=ffffff&fg=000000&s=0&latex=", 
		"http://chart.apis.google.com/chart?cht=tx&chl=",		
		"http://www.quantnet.com/cgi-bin/mathtex.cgi?",
		"http://tex.72pines.com/latex.php?latex="
		);
		?>
	<table class="form-table">
		<tbody>
		<tr>
			<th>
				<label><input name="latex_img_server" type="radio" value="" class="tog"
				<?php echo get_option('latex_img_server')!=""?"":"checked='checked'";?>>
				Turn off Images			</label>
			</th>
			<td>
				MathJax is enough for me, I don't want to display formula as images.
			</td>
		</tr>		
		<tr>
			<th><label><input name="latex_img_server" type="radio" 
			value="<?php echo $latex_img_servers[0];?>" class="tog" 
			<?php echo get_option('latex_img_server')==$latex_img_servers[0]?'checked="checked"':''?>>
			WordPress.com</label></th>
			<td><img src='<?php echo $latex_img_servers[0]."%5Csigma_t%5E2%3D%281-%5Clambda%29%20%5Csigma_%7Bt-1%7D%5E2%2B%5Clambda%20r_t%5E2"?>';/>
			<code><?php echo $latex_img_servers[0];?></code></td>
		</tr>
		<tr>
			<th><label><input name="latex_img_server" type="radio" 
			value="<?php echo $latex_img_servers[1];?>" class="tog" 
			<?php echo get_option('latex_img_server')==$latex_img_servers[1]?'checked="checked"':''?>>
			Google Charts</label></th>
			<td><img src='<?php echo $latex_img_servers[1]."%5Csigma_t%5E2%3D%281-%5Clambda%29%20%5Csigma_%7Bt-1%7D%5E2%2B%5Clambda%20r_t%5E2"?>';/>
			<code><?php echo $latex_img_servers[1];?></code></td>
		</tr>		
		<tr>
			<th><label><input name="latex_img_server" type="radio" 
			value="<?php echo $latex_img_servers[2];?>" class="tog" 
			<?php echo get_option('latex_img_server')==$latex_img_servers[2]?'checked="checked"':''?>>
			MathTex on quantnet</label></th>
			<td><img src='<?php echo $latex_img_servers[2]."%5Csigma_t%5E2%3D%281-%5Clambda%29%20%5Csigma_%7Bt-1%7D%5E2%2B%5Clambda%20r_t%5E2"?>';/>
			<code><?php echo $latex_img_servers[2];?></code></td>
		</tr>		
		<tr>
			<th><label><input name="latex_img_server" type="radio" 
			value="<?php echo $latex_img_servers[3];?>" class="tog" 
			<?php echo get_option('latex_img_server')==$latex_img_servers[3]?'checked="checked"':''?>>
			MimeTex on 72Pines</label></th>
			<td><img src='<?php echo $latex_img_servers[3]."%5Csigma_t%5E2%3D%281-%5Clambda%29%20%5Csigma_%7Bt-1%7D%5E2%2B%5Clambda%20r_t%5E2"?>';/>
			<code><?php echo $latex_img_servers[3];?></code></td>
		</tr>
		<tr>
			<th>
				<label><input name="latex_img_server" type="radio" value="custom" class="tog"
				<?php echo (in_array(get_option('latex_img_server'), $latex_img_servers)||get_option('latex_img_server')=="")?"":"checked='checked'";?>>
				Custom Service			</label>
			</th>
			<td>
				<input name="latex_img_custom_server" type="text" value="<?php echo get_option('latex_img_server');?>" class="regular-text code" style="width:50em;">
			</td>
		</tr>
		<tr>
			<th scope="row">Custom CSS to use with the LaTeX images</th>
			<td>
				<textarea name="latex_imgcss" rows="4" cols="50"><?php echo get_option('latex_imgcss');?></textarea>
			</td>
		</tr>
	</tbody></table>
	<h3>MathJax Server</h3>
	<p>MathJax is an open source JavaScript display engine for mathematics that works in all modern browsers.
	It uses modern CSS and web fonts, instead of equation images or Flash, so equations scale with surrounding text at all zoom levels.
	See what it looks like in <a href='mathjax.org'>MathJax.org</a> or <a href='http://zhiqiang.org/blog/finance/school/risk-aversion-in-portfolio-optimization.html'>zhiqiang.org</a></p>
	<table class="form-table">
		<tr>
			<th><label><input name="mathjax_server" type="radio" 
			value="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default" 
			class="tog" <?php echo (get_option('mathjax_server')=='http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default')?'checked="checked"':'';?>>MathJax CDN</label></th>
			<td><code>http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default</code><br/>
			You need to accept 
				<a href="http://www.mathjax.org/download/mathjax-cdn-terms-of-service/">MathJax CDN Terms of Service</a> before using it</td>
		</tr>
		<tr>
			<th>
				<label><input name="mathjax_server" type="radio" 
				<?php echo get_option('mathjax_server')!=''&&get_option('mathjax_server')!='http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default'?'checked="checked"':'';?>
				value="custom" class="tog">
				Custom Service			</label>
			</th>
			<td>
				<input name="mathjax_custom_server" type="text" value="<?php echo get_option('mathjax_server');?>" 
				class="regular-text code" style="width:50em;">
				<br/>
				<a href="http://www.mathjax.org/docs/1.1/installation.html">How to get your own MathJax?</a>
			</td>
		</tr>
		<tr>
			<th><label><input name="mathjax_server" type="radio" 
			 <?php echo get_option('mathjax_server')==''?'checked="checked"':'';?>
			value="" class="tog">Turn off</label></th>
			<td>Displaying fourmula as images is good enough for me, and I don't want to use MathJax.</td>
		</tr>		
		<tr>
			<th scope="row">Custom inline MathJax Config, refer <a href="http://www.mathjax.org/docs/2.0/options/index.html#configuration">here</a> to find the options:</th>
			<td>
				<textarea name="latex_mathjax_config" rows="10" cols="100"><?php echo stripcslashes(get_option('latex_mathjax_config'));?></textarea>
			</td>
		</tr>
	</tbody></table>	
	
	
	<p class="submit"><input type="submit" name='update_latex_option' class="button-primary" value="Update LaTeX Options"></p>
	</form>
	
	<form method="post">
		<h3>Delete Caches</h3>
		<p>For displaying fourmula more quickly, the generated images are cached under directory
		"/wp-content/plugins/latex/cache/". When you 
		change the latex image server which generate different images from previously one, 
		you need to delete your caches before the images are refreshing.</p>
		<p class="submit"><input type="submit" name='delete_cache' class="button-primary" value="Delete LaTex Caches"></p>
	</form>
</div>