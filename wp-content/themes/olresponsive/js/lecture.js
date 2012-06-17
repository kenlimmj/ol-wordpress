jwplayer('container').setup({
  'flashplayer': '/wp-content/themes/olresponsive/jwplayer/player.swf',
  'width':'100%',
  'height':'100%',
  'plugins': {
    'hd-2': {'state': 'true'},
    'sharing-3': {link: ''},
    'timeslidertooltipplugin-3': {'fontsize': '12'}
  },
  'controlbar': 'over',
  'logo': {
    'file': '/wp-content/themes/olresponsive/img/Logo-Player.png',
    'position': 'top-left',
  },
  'skin': '/wp-content/themes/olresponsive/jwplayer/schoon/schoon.zip',
  'screencolor': '#ffffff',
  'provider': 'youtube',
});