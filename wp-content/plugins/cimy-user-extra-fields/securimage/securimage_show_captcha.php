<?php

require_once 'securimage.php';

$img = new securimage();

// $img->image_width = 278;
$img->image_width = 250;
$img->image_height = 80;
// $img->ttf_file = 'AHGBold.ttf';
$img->perturbation = 0.85;
$img->image_bg_color = new Securimage_Color(0x0, 0x0, 0x0);
$img->text_color = new Securimage_Color(0xff, 0xff, 0xff);
$img->text_transparency_percentage = 10;
$img->use_transparent_text = true;
$img->text_angle_minimum = -10;
$img->text_angle_maximum = 10;
$img->num_lines = 0;
$img->line_color = new Securimage_Color(0xff, 0xaff, 0xff);

$img->show('backgrounds/bg6.png');
