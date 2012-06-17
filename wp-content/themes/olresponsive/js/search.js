jQuery(document).ready(function (jQuery){
        var acs_action = 'myprefix_autocompletesearch';
        jQuery("#s").autocomplete({
            source: function(req, response){
                jQuery.getJSON(MyAcSearch.url+'?callback=?&action='+acs_action, req, response);
            },
            select: function(event, ui) {
                window.location.href=ui.item.link;
            },
            minLength: 3,
        });
    });