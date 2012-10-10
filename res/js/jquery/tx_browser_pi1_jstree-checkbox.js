/**
 *
 * Copyright (c) 2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.1.19
 */



$( document ).ready( function( )
{

  if( $( "###SELECTOR_01###" ).length )
  {
    $("###SELECTOR_01###").jstree({
      "themes" : {
        "theme" : "###THEME_01###",
        "dots"  : ###DOTS_01###,
        "icons" : ###ICONS_01###
      },
      "checkbox" : {
        "real_checkboxes"       : true,
        "real_checkboxes_names" : function (n) { return [( "check_" + n[0].id ), 1]; }
      },
      "plugins" : ["themes", "html_data", "checkbox", "sort", "ui", "cookies"]
    });
  }
});