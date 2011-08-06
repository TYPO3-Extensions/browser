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
    // User has clicked the record browser
    // live: Attach a handler to the event for all elements which match the current selector, now and in the future.
    //       see: http://api.jquery.com/live/
  $( ".c2479-record-browser" ).live(    
    'click',
    function( e ) {
        // Don't execute the click
      e.preventDefault( );
        // Update the content with the id #c2479-singleview-1
      $( this ).t3browser( 'update', "#c2479-singleview-1", $( this ).attr( "href" ) + "?type=28562 #c2479-singleview-1 > *" );
    }
  );
    // User has clicked the record browser

});