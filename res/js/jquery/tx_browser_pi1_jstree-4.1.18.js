/**
 *
 * Copyright (c) 2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.1.18
 *
 * jquery.jstree-x.x.x.js is needed:
 *   http://docs.jquery.com/Plugins/jstree
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */



$( document ).ready( function( )
{

  if( $( "###SELECTOR_01###" ).length )
  {
    $("###SELECTOR_01###").jstree({
      "themes" : {
        "theme" : "###THEME_01###",
        "dots"  : ###DOTS_01##,
        "icons" : ###ICONS_01###
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_02###" ).length )
  {
    $("###SELECTOR_02###").jstree({
      "themes" : {
        "theme" : "###THEME_02###",
        "dots"  : ###DOTS_02##,
        "icons" : ###ICONS_02###
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_03###" ).length )
  {
    $("###SELECTOR_03###").jstree({
      "themes" : {
        "theme" : "###THEME_03###",
        "dots"  : ###DOTS_03##,
        "icons" : ###ICONS_03###
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_04###" ).length )
  {
    $("###SELECTOR_04###").jstree({
      "themes" : {
        "theme" : "###THEME_04###",
        "dots"  : ###DOTS_04##,
        "icons" : ###ICONS_04###
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_05###" ).length )
  {
    $("###SELECTOR_05###").jstree({
      "themes" : {
        "theme" : "###THEME_05###",
        "dots"  : ###DOTS_05##,
        "icons" : ###ICONS_05###
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }

});