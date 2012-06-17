<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" lang="en" xmlns:fb="http://ogp.me/ns/fb#"> <!--<![endif]-->
<head>
  <!-- Basic Page Needs
  ================================================== -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=9" />
  <meta property="og:title" content="openlectures <?php wp_title('-'); ?>" />
  <meta property="og:type" content="school" />
  <meta property="og:url" content="<?php echo 'http://sg.openlectures.org'.esc_url($_SERVER['REQUEST_URI']); ?>" />
  <meta property="og:image" content="/wp-content/themes/olresponsive/img/Logo-Square-Small.png" />
  <meta property="og:site_name" content="openlectures"/>
  <meta property="og:description" content="openlectures provides free online lectures to any student who needs revision, a second explanation or just wants to learn ahead. Everything is freely available – because we believe that effort, and not money, should decide success."/>
  <meta property="og:email" content="hello@sg.openlectures.org"/>
  <meta property="og:country-name" content="Singapore"/>
  <meta property="fb:admins" content="100000730951420" />
  <title>openlectures <?php wp_title('-'); ?></title>

  <!-- Mobile Specific Metas
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- CSS
  ================================================== -->
  <link rel="stylesheet" href="/wp-content/themes/olresponsive/style.css">
  <link rel="stylesheet" href="/wp-content/themes/olresponsive/ol.css">
  <link rel="stylesheet" href="/wp-content/themes/olresponsive/font-awesome.css">
  <link rel="stylesheet" href="/wp-content/themes/olresponsive/docs.css">

  <!--Scripts
  ================================================== -->
  <?php add_action('wp_enqueue_scripts', 'call_scripts'); ?>
  <?php wp_head(); ?>
  <?php flush(); ?>
</head>