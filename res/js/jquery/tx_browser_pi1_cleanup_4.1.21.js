/**
 *
 * Copyright (c) 2012 - Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * @version 4.1.21
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */


/**
 * cleanup_afterAJAXrequest( ): This method calls functions, which are needed
 *                              after an AJAX request.
 *                              * Reload of CSS styles 
 * @version 0.0.3
 */

function cleanup_afterAJAXrequest( )
{
    /////////////////////////////////////////////////////
    //
    // jQuery button

    // Try to reload CSS classes for buttons
  //if( 1 == 0 ) {
    try {
      $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
    }
    catch( err )
    {
      // jQuery is compiled without button method. Don't worry!
    }
  //}
    // Try to reload CSS classes for buttons
    // jQuery button



    /////////////////////////////////////////////////////
    //
    // jQuery plugin jstree

  try {
    $.jstree._themes = "###PATH_TO_THEMES###";
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
  try {
    if( $( "###SELECTOR_01###" ).length )
    {
      $("###SELECTOR_01###").jstree({
        "checkbox" : {
          "override_ui" : true
        },
        "cookies" : {
          "save_loaded"   : "jstreeTreeview01_loaded",
          "save_opened"   : "jstreeTreeview01_opened",
          "save_selected" : "jstreeTreeview01_selected"
        },
        "themes" : {
          "theme" : "###THEME_01###",
          "dots"  : ###DOTS_01###,
          "icons" : ###ICONS_01###
        },
        "plugins" : [ ###PLUGINS_01### ]
      });
    }
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
  try {
    if( $( "###SELECTOR_02###" ).length )
    {
      $("###SELECTOR_02###").jstree({
        "checkbox" : {
          "override_ui" : true
        },
        "cookies" : {
          "save_loaded"   : "jstreeTreeview02_loaded",
          "save_opened"   : "jstreeTreeview02_opened",
          "save_selected" : "jstreeTreeview02_selected"
        },
        "themes" : {
          "theme" : "###THEME_02###",
          "dots"  : ###DOTS_02###,
          "icons" : ###ICONS_02###
        },
        "plugins" : [ ###PLUGINS_02### ]
      });
    }
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
  try {
    if( $( "###SELECTOR_03###" ).length )
    {
      $("###SELECTOR_03###").jstree({
        "checkbox" : {
          "override_ui" : true
        },
        "cookies" : {
          "save_loaded"   : "jstreeTreeview03_loaded",
          "save_opened"   : "jstreeTreeview03_opened",
          "save_selected" : "jstreeTreeview03_selected"
        },
        "themes" : {
          "theme" : "###THEME_03###",
          "dots"  : ###DOTS_03###,
          "icons" : ###ICONS_03###
        },
        "plugins" : [ ###PLUGINS_03### ]
      });
    }
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
  try {
    if( $( "###SELECTOR_04###" ).length )
    {
      $("###SELECTOR_04###").jstree({
        "checkbox" : {
          "override_ui" : true
        },
        "cookies" : {
          "save_loaded"   : "jstreeTreeview04_loaded",
          "save_opened"   : "jstreeTreeview04_opened",
          "save_selected" : "jstreeTreeview04_selected"
        },
        "themes" : {
          "theme" : "###THEME_04###",
          "dots"  : ###DOTS_04###,
          "icons" : ###ICONS_04###
        },
        "plugins" : [ ###PLUGINS_04### ]
      });
    }
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
  try {
    if( $( "###SELECTOR_05###" ).length )
    {
      $("###SELECTOR_05###").jstree({
        "checkbox" : {
          "override_ui" : true
        },
        "cookies" : {
          "save_loaded"   : "jstreeTreeview05_loaded",
          "save_opened"   : "jstreeTreeview05_opened",
          "save_selected" : "jstreeTreeview05_selected"
        },
        "themes" : {
          "theme" : "###THEME_05###",
          "dots"  : ###DOTS_05###,
          "icons" : ###ICONS_05###
        },
        "plugins" : [ ###PLUGINS_05### ]
      });
    }
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
    // jQuery plugin jstree

}
