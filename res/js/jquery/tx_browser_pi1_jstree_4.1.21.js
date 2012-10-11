/**
 *
 * Copyright (c) 2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.1.21
 *
 * jquery.jstree-x.x.x.js is needed:
 *   http://www.jstree.com/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

/**
 *
 * BE AWARE
 * - Any changing must handled in both 
 *   - tx_browser_pi1_jstree_x.x.x.js
 *   - tx_browser_pi1_cleanup_x.x.x.js
 */

$( document ).ready( function( )
{

  $.jstree._themes = "###PATH_TO_THEMES###";
  if( $( "###SELECTOR_01###" ).length )
  {
    $("###SELECTOR_01###").jstree({
      "checkbox" : {
        "override_ui" : true
      },
      "themes" : {
        "theme" : "###THEME_01###",
        "dots"  : ###DOTS_01###,
        "icons" : ###ICONS_01###
      },
      "plugins" : [ ###PLUGINS_01### ]
    });
  }
  if( $( "###SELECTOR_02###" ).length )
  {
    $("###SELECTOR_02###").jstree({
      "checkbox" : {
        "override_ui" : true
      },
      "themes" : {
        "theme" : "###THEME_02###",
        "dots"  : ###DOTS_02###,
        "icons" : ###ICONS_02###
      },
      "plugins" : [ ###PLUGINS_02### ]
    });
  }
  if( $( "###SELECTOR_03###" ).length )
  {
    $("###SELECTOR_03###").jstree({
      "checkbox" : {
        "override_ui" : true
      },
      "themes" : {
        "theme" : "###THEME_03###",
        "dots"  : ###DOTS_03###,
        "icons" : ###ICONS_03###
      },
      "plugins" : [ ###PLUGINS_03### ]
    });
  }
  if( $( "###SELECTOR_04###" ).length )
  {
    $("###SELECTOR_04###").jstree({
      "checkbox" : {
        "override_ui" : true
      },
      "themes" : {
        "theme" : "###THEME_04###",
        "dots"  : ###DOTS_04###,
        "icons" : ###ICONS_04###
      },
      "plugins" : [ ###PLUGINS_04### ]
    });
  }
  if( $( "###SELECTOR_05###" ).length )
  {
    $("###SELECTOR_05###").jstree({
      "checkbox" : {
        "override_ui" : true
      },
      "themes" : {
        "theme" : "###THEME_05###",
        "dots"  : ###DOTS_05###,
        "icons" : ###ICONS_05###
      },
      "plugins" : [ ###PLUGINS_05### ]
    });
  }

});

function generateHiddenFieldsForTree( treeId, tableField ) 
{
    // RETURN : there isn't any treeId
  if( ! $( treeId ).length )
  {
    return;
  }

    // RETURN : table.field isn't configured proper
  if( ! tableField )
  {
    var prompt = "table.field is missing for " + treeId + ". Please take care of a proper TypoScript. Look for i.e: {$plugin.tx_browser_pi1.jQuery.plugin.jstree.tablefield_01}" ;
    alert( prompt );
    return;
  }
  
  var checked_ids = [];
  var name = "tx_browser_pi1[" + tableField + "][]"; 
  //alert( treeId + ": " + name );

    // Append an input field for each selected <li>-item to the current form
  $( treeId ).jstree( "get_checked" , null, true ).each(function( )
  {
      // Get current record uid
    var thisId         = this.id;
    var thisIdSplitted = thisId.split( "_" );
    var recordUid      = thisIdSplitted[ thisIdSplitted.length - 1 ];

    // Append an input field with the record uid
    if( recordUid )
    {
      $( ".searchbox > form" ).append('<input type="hidden" name="' + name + '" value="' + recordUid + '" />');
    }
  });

}
$( function ( ) {
  $( ".searchbox > form" ).submit( function ( )
  {
    generateHiddenFieldsForTree( "###SELECTOR_01###", "###TABLEFIELD_01###" );
    generateHiddenFieldsForTree( "###SELECTOR_02###", "###TABLEFIELD_02###" );
    generateHiddenFieldsForTree( "###SELECTOR_03###", "###TABLEFIELD_03###" );
    generateHiddenFieldsForTree( "###SELECTOR_04###", "###TABLEFIELD_04###" );
    generateHiddenFieldsForTree( "###SELECTOR_05###", "###TABLEFIELD_05###" );
  });
});