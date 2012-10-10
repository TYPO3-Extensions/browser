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
        "real_checkboxes" : true
      },
      "plugins" : ["themes", "html_data", "checkbox", "sort", "ui", "cookies"]
    })
    .bind("select_node.jstree", function (event, data) { 
      // `data.rslt.obj` is the jquery extended node that was clicked
      alert(data.rslt.obj.attr("id"));
    })
  }
});