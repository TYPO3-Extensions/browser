/**
 *
 * Copyright (c) 2012 - Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * @version 0.0.3
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
  try {
    $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
  }
  catch( err )
  {
    // jQuery is compiled without button method. Don't worry!
  }
    // Try to reload CSS classes for buttons
    // jQuery button



    /////////////////////////////////////////////////////
    //
    // jQuery plugin jstree

  try {
    $( "#tx_greencars_manufacturer_title" ).jstree({ 
      "themes" : {
        "theme" : "classic",
        "dots"  : true,
        "icons" : false
      },
      "checkbox"	: {
        "override_ui" : true
      },
      "plugins" : [ "themes", "html_data", "checkbox",  "ui", "cookies" ]
    });
  }
  catch( err )
  {
    // jQuery plugin jstree isn't included. Don't worry!
  }
    // jQuery plugin jstree

}
