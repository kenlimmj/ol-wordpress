=== LaTeX for WordPress ===
Contributors: zhiqiang
Donate link: 
Tags: LaTeX, formatting, mimetex,tex, math, equations, mathematics, formula
Requires at least: 2.3
Tested up to: 3.31
Stable tag: trunk

This plugin provide an easy and elegant solution to add and display your mathematical fourmula. 

== Description ==

verion logs:

ver 3.4: Now you can turn off displaying formula as images. Use this when your server has problem to fetch LaTeX images.

ver 3.3: Fix the bug to display LaTeX source code.

ver 3.2: Load MathJax only when needed.

ver 3.0: Using MathJax and Images to display LaTeX formula.

Using MathJax and images, this plugin provides a general and elegant solution to add and display your mathematical fourmula, no matter the visitors are visiting your blog or read from Google Reader. 

You can type the formula in LaTeX, in title, posts, pages and comments:

* `\(\alpha+\beta\geq\gamma\)` or `$$\alpha+\beta\geq\gamma$$` add an inline formula
* `\[\alpha+\beta\geq\gamma\]` or `$$!\alpha+\beta\geq\gamma$$` add an LaTeX equation in math mode(it will be displayed centerly in a single line).
* `\(\alpha+\beta\geq\gamma!\)` display the source of the LaTeX formula. Just add a `!` before `\)` or `\]`.

This plugin provides (and recommend) you a choice  to use MathJax to display formula in your blog. MathJax is an open source JavaScript display engine for mathematics that works in all modern browsers. It uses modern CSS and web fonts, instead of equation images or Flash, so equations scale with surrounding text at all zoom levels. I would say that MathJax turns the previous ugly mathematics fourmula on web into arts. You can check it in <a href='mathjax.org'>mathjax.org</a> or <a href='http://zhiqiang.org/blog/finance/school/risk-aversion-in-portfolio-optimization.html'>zhiqiang.org</a>.

The plugin uses copy of Mathjax from their CDN Service by default, you can also install your own MathJax. It's easy, just following the link in the setting page of this plugin.

However, MathJax is not perfect. It's somewhat slow to load in the first time; It requires the users turn on their browsers' JavaScript; The fourmula don't work on Google Reader. To complement this, The plugin also uses the LaTeX image service to generate images for your mathematical fourmula. In the case of the MathJax was not loading, the plugin displays the images instead. So it provide seamless solution to display mathematical formula of your posts everywhere.

There are lots of websites provide such services, for example, wordpress.com and Google Charts. The plugin provide four candidates for you, and you can choose any of them or customize it to use your own LaTeX image generating service.

Other features are added in version 3.2+:

* The mathjax script is loaded only needed. This would spead up your blog a lot.


Chinese document: http://zhiqiang.org/blog/it/LaTeX-for-wordpress.html


== Installation ==
= Installation instruction =

1. Upload `LaTeX` fold to the `/wp-content/plugins/` directory
1. Activate the plugin `LaTeX for WordPress` through the 'Plugins' menu in WordPress
1. The diretory `/wp-content/plugins/LaTeX/cache` should be writable by your webserver (chmod 777 will do the trick).
1. done

= uninstall instruction =

1. deactivate the plugin or remove the plugin files.

= More configurations =

In most cases, you don't need any more configurations. However, you have lots of choices in the setting page of this plugin if you like.

In the setting page, you can choose or customize the LaTeX image server and the MathJax server, or you can turn off the MathJax if you don't like the slow MathJax.


= Install your own LaTeX image service = 

As I known, MimeTex is the easiest one to build up:

* download appropriate mimetex executable program from `http://www.forkosh.com/mimetex.html`. change the file name to `mimetex.cgi` or `mimetex.exe`(the postfix depend on your system). Upload it to your `/cgi-bin/` diretory, make it executable by your webserver(chmod 777 will do the trick).
* check url `http://yourdomain/cgi-bin/mimtex.cgi?\alpha\geq\beta` to make sure it works.
* change `var $server` to appropriate one like `var $server = "http://yourdomain/cgi-bin/mimetex.cgi?";` 
* You need to change the `$img_format` to corresponded image type your service generated. As in the case of MimeTex, the generated type is `gif`.

== Frequently Asked Questions ==

= The background of my blog is black and the words are black. The equation by this plugin is black  and the backgound is white. How can I make them consistent? =

When the generated images are transparent, they are fit to any background. Otherwise, you need to use a LaTeX image service which supports custom background and foreground color. `http://l.wordpress.com/LaTeX.php?bg=ffffff&fg=000000&LaTeX=` is a good candidate. The bg parameter defines background color and fg parameter defines foreground color, you can customize them to any RGB colors.

== Screenshots ==

1. This is the first screen shot
2. This is the second screen shot
3. This is the third screen shot