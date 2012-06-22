# OL Responsive Theme
The default theme for [Open Lectures](http://openlectures.sg). This theme builds on the [Twitter Bootstrap Framework](http://twitter.github.com/bootstrap) and adds a functionality layer to encourage coherence with WordPress. 

## File Reference
**Core theme files:**

* style.css (containing bootstrap.css)
* ol.css (containing local edits to bootstrap.css prioritized via the ``!important`` tag)
* /font (containing the fonts "CMU Bright", used in ``p`` and "Geosans Light", used in ``h1,h2,h3,h4,h5,h6``)

**WP template files:**

* archive.php (Currently unused)
* attachment.php (Currently unused)
* author.php (Themes ~/author/<user>)
* category.php (Themes ~/subject/<subject>)
* comments.php (Currently unused)
* footer.php (Themes all page footers called via ``get_footer()``)
* functions.php (Provides PHP function library and misc. tweaks)
* header.php (Themes the navigation header and calls CSS/JS via ``get_header()``)
* home.php (Themes the landing page)
* index.php (Fall-back for pages not provided for by their own templates)
* page.php (Themes all pages, e.g. ~/press, ~/about-us, ~/terms, etc.)
* search.php (Currently unused)
* single.php (Themes ~/subject/<subject>/<post>)

## Versioning
OL-Responsive will be maintained under the Semantic Versioning guidelines as much as possible. 

Releases will be numbered with the following format:

``
<major>.<minor>.[<Framework>]
``
And constructed with the following guidelines:

* Breaking backward compatibility or changing the design language bumps the major
* New additions, bug fixes and misc. changes that do not break backward compatibility bump the minor
* Upgrading Twitter Bootstrap bumps the framework to the corresponding version number

Visit [SemVer](http://semver.org) for more details.

## Social Media
Updates on Open Lectures, information about site down-time and other miscellaneous announcements can be obtained by following us on Twitter [@openlecturessg](http://twitter.com/openlecturessg) or on [Facebook](http://www.facebook.com/OpenLectures)

## Author
Kenneth Lim

* [@kenlimmj](http://twitter.com/kenlimmj)
* [Email](kenneth.lim@openlectures.sg)