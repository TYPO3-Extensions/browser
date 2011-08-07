/**
 *
 * Copyright (c) 2011 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 0.0.1
 *
 * jquery.t3browser-x.x.x.js is needed:
 *   http://docs.jquery.com/Plugins/t3browser
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */



$( document ).ready( function( )
{
    // UI for buttons in the searchbox form
  $(function()
  {
    $( "input:submit, input:button, a.backbutton", ".tx-browser-pi1" ).live( 'button' );
  });


    // User has clicked the record browser
    // live: Attach a handler to the event for all elements which match the current selector, now and in the future.
    //       see: http://api.jquery.com/live/
  $( ".c###TT_CONTENT.UID###-record-browser" ).live(    
    'click',
    function( e ) {
        // Don't execute the click
      e.preventDefault( );
        // Update the content with the id #c###TT_CONTENT.UID###-singleview-1
      url = $( this ).t3browser( 'url_autoQm', $( this ).attr( "href" ), "type=###TYPENUM###" );
      $( this ).t3browser( 'update', "#c###TT_CONTENT.UID###-singleview-1", url + " #c###TT_CONTENT.UID###-singleview-1 > *" );
    }
  );
    // User has clicked the record browser

});
