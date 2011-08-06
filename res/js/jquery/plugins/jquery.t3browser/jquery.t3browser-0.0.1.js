/**
 * jQuery t3_browser Plugin 0.0.1
 *
 * http://docs.jquery.com/Plugins/t3browser
 *
 * Copyright (c) 2011 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

;(function( $ )
{
  
  var methods = {
    init :    function( options ) 
              {
              },
    show :    function( )
              {
              },
    hide :    function( )
              {
              },
    update :  function( html_element, url )
              {
                  // Add an opacity to the html element
                $( html_element ).addClass( "opacity08" );

                  // Cover the html element with a loading gif
                $( html_element ).prepend( "\t<div id='tx-browser-pi1-loader'></div>\n" );   
                  // Get the size of the html element
                var heightWiPx  = $( html_element ).css( "height" );
                var widthWiPx   = $( html_element ).css( "width" );
                  // Set the loader to the size of the html element
                $( "#tx-browser-pi1-loader" ).css( "height",        heightWiPx        );
                $( "#tx-browser-pi1-loader" ).css( "width",         widthWiPx         );
                $( "#tx-browser-pi1-loader" ).css( "margin-bottom", "-" + heightWiPx  );
                  // Fade in the loader
                $( "#tx-browser-pi1-loader" ).fadeIn( 150 );
                  // Cover the html element with a loading gif

                  // Send the AJAX request
                  // Replace the content of the html element with the delivered data
                $( html_element ).load(
                  url,
                  function( )
                  {
                      // Fade out the loader
                    $( "#tx-browser-pi1-loader" ).fadeOut( 500, function( )
                    {
                      $( this ).remove( );
                    });
                      // Remove the opacity of the html element
                    $( html_element ).removeClass( "opacity08" );
                  }
                );
                  // Send the AJAX request
              }
                // update( ) 
  };
  
  $.fn.t3browser = function( method )
  {
      // See http://docs.jquery.com/Plugins/Authoring#Plugin_Methods

      // Method calling logic
    if ( methods[method] ) 
    {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    }
    else if ( typeof method === "object" || ! method )
    {
      return methods.init.apply( this, arguments );
    }
    else
    {
      $.error( "Method " +  method + " does not exist on jQuery.tooltip" );
    }    
  };
      // Method calling logic

})( jQuery );


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