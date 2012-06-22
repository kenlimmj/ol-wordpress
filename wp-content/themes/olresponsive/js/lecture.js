jwplayer('container').setup({
  // 'flashplayer': '/wp-content/themes/olresponsive/jwplayer/player.swf',
  'width':'100%',
  'height':'100%',
  'modes': [
      { 'type': 'flash', 'src': '/wp-content/themes/olresponsive/jwplayer/player.swf' },
      { 'type': 'html5' },
      { 'type': 'download' }
  ],
  'plugins': {
    'hd-2': {'state': 'true'},
    'sharing-3': {link: ''},
    'timeslidertooltipplugin-3': {'fontsize': '12'},
    'gapro-2': {}
  },
  'controlbar': 'over',
  'logo': {
    'file': '/wp-content/themes/olresponsive/img/Logo-Player.png',
    'position': 'top-left',
  },
  'skin': '/wp-content/themes/olresponsive/jwplayer/minima/minima.zip',
  'screencolor': '#ffffff',
  'image': '/wp-content/themes/olresponsive/jwplayer/poster.png'
});