/**
 *
 * Copyright (c) 2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 3.9.21
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
        "theme" : "###THEME###",
        "dots"  : true,
        "icons" : true
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_02###" ).length )
  {
    $("###SELECTOR_02###").jstree({
      "themes" : {
        "theme" : "###THEME###",
        "dots"  : true,
        "icons" : true
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_03###" ).length )
  {
    $("###SELECTOR_03###").jstree({
      "themes" : {
        "theme" : "###THEME###",
        "dots"  : true,
        "icons" : true
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_04###" ).length )
  {
    $("###SELECTOR_04###").jstree({
      "themes" : {
        "theme" : "###THEME###",
        "dots"  : true,
        "icons" : true
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }
  if( $( "###SELECTOR_05###" ).length )
  {
    $("###SELECTOR_05###").jstree({
      "themes" : {
        "theme" : "###THEME###",
        "dots"  : true,
        "icons" : true
      },
      "plugins" : ["themes", "html_data", "cookies"]
    });
  }

});