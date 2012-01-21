/**
 *
 * Copyright (c) 2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 0.0.1
 *
 * jquery.jstree-x.x.x.js is needed:
 *   http://docs.jquery.com/Plugins/tstree
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */



$( document ).ready( function( )
{

  $("###SELECTOR###").jstree({
    "themes" : {
      "theme" : "###THEME###",
      "dots"  : true,
      "icons" : true
    },
    "plugins" : ["themes", "html_data", "cookies"]
  });

});
