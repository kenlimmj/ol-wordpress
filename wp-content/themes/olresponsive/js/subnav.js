    var navTop = jQuery('.subnav').length && jQuery('.subnav').offset().top - 40
      , isFixed = 0

    processScroll()

    jQuery(window).on('scroll', processScroll)

    function processScroll() {
      var i, scrollTop = jQuery(window).scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        jQuery('.subnav').addClass('subnav-fixed')
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        jQuery('.subnav').removeClass('subnav-fixed')
      }
    }