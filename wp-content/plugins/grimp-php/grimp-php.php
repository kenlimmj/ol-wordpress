<?php

/*
Plugin Name: Grimp - PHP
Plugin URI: http://git.grimp.eu/?p=wpp-php.git
Description: This plugin allows you to use php code (syntax: &#60;?php {code} ?&#62;) in your posts/pages, if you have the "unfiltered_html" capability.
Version: 0.1
Author: Grimp di Fabio Alessandro Locati
Author URI: http://grimp.eu
License: GPLv2
*/

/*  Copyright 2010-2011 Grimp di Fabio Alessandro Locati (email: legal@grimp.eu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


### mask code before going to the nasty balanceTags ###
function php_exec_pre($text) {
  $textarr = preg_split("/(<\\?php.*\\?>)/Us", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
  $stop = count($textarr);// loop stuff
  $output = "";
  for ($phpexec_i = 0; $phpexec_i < $stop; $phpexec_i++) {
    $content = $textarr[$phpexec_i];
    if (preg_match("/^<\\?php(.*)\\?>/Us", $content, $code)) { // If it's a phpcode
      $content = '[phpcode]' . base64_encode($code[1]) . '[/phpcode]';
    }
    $output .= $content;
  }
  return $output;
}

### unmask code after balanceTags ###
function php_exec_post($text) {
  $textarr = preg_split("/(\\[phpcode\\].*\\[\\/phpcode\\])/Us", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
  $stop = count($textarr);// loop stuff
  $output = "";
  for ($phpexec_i = 0; $phpexec_i < $stop; $phpexec_i++) {
    $content = $textarr[$phpexec_i];
    if (preg_match("/^\\[phpcode\\](.*)\\[\\/phpcode\\]/Us", $content, $code)) { // If it's a phpcode
      $content = '<?php' . base64_decode($code[1]) . '?>';
    }
    $output .= $content;
  }
  return $output;
}

### main routine ###
function php_exec_process($phpexec_text) {
  if(author_can(get_the_ID(),"unfiltered_html"))
    $phpexec_doeval = true;

  // capture the tags as well as in between
  $phpexec_textarr = preg_split("/(<\\?php.*\\?>)/Us", $phpexec_text, -1, PREG_SPLIT_DELIM_CAPTURE);
  $phpexec_stop = count($phpexec_textarr);// loop stuff
  $phpexec_output = "";
  for ($phpexec_i = 0; $phpexec_i < $phpexec_stop; $phpexec_i++) {
    $phpexec_content = $phpexec_textarr[$phpexec_i];
    if (preg_match("/^<\\?php(.*)\\?>/Us", $phpexec_content, $phpexec_code)) { // If it's a phpcode
      $phpexec_php = $phpexec_code[1];
      if ($phpexec_doeval) {
        ob_start();
        eval($phpexec_php);
        $phpexec_output .= ob_get_clean();
      } else
        $phpexec_output .= htmlspecialchars($phpexec_php);
    } else
      $phpexec_output .= $phpexec_content;
  }
  return $phpexec_output;
}

add_filter('content_save_pre', 'php_exec_pre', 29);
add_filter('content_save_pre', 'php_exec_post', 71);
add_filter('the_content', 'php_exec_process', 2);

add_filter('excerpt_save_pre', 'php_exec_pre', 29);
add_filter('excerpt_save_pre', 'php_exec_post', 71);
add_filter('the_excerpt', 'php_exec_process', 2);
