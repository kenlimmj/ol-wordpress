jwplayer('container').setup({
  'flashplayer': '/wp-content/themes/olresponsive/jwplayer/player.swf',
  'width':'100%',
  'height':'100%',
  'plugins': {
    'hd-2': {'state': 'true'},
    'sharing-3': {link: ''}
  },
  'controlbar': 'over',
  'logo': {
    'file': '/wp-content/themes/olresponsive/img/Logo-Player.png',
    'position': 'top-left',
  },
  'skin': 'http://localhost/wp-content/themes/olresponsive/jwplayer/schoon/schoon.zip',
  'screencolor': '#ffffff',
});

jwplayer('container').onTime(
     function(event) {
     $video_position = +event.position;
     $a = '9';
     $b = '41';
     $c = '72';
     $d = '120';
     $e = '141';
     if ($video_position > $a && $video_position < $b) {
     jQuery('#9').addClass("active");
     }
     if ($video_position > $b && $video_position < $c) {
     jQuery('#9').removeClass("active");
     jQuery('#41').addClass("active");
     }
     if ($video_position > $c && $video_position < $d) {
     jQuery('#41').removeClass("active");
     jQuery('#72').addClass("active");
     }
     if ($video_position > $d && $video_position < $e) {
     jQuery('#72').removeClass("active");
     jQuery('#120').addClass("active");
     }
     }
    );