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

;(function( $ ){
  
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
    update :  function( id, url )
              {
                //$(id).slideUp("slow");
                // alert(href);
                //location.href = url;
                $.get(url, function(data){
                  alert("Data Loaded: " + data);
                });
              }
  };
  
  $.fn.t3browser = function( method ) {
    
      // Method calling logic
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
    }    
  
  };
})( jQuery );


$(document).ready(function() {

    // User has clicked on an item of the record browser inside of the content element with the uid 2479
  $(".c2479-record-browser").click(
    function(e) {
        // Don't execute the click
      e.preventDefault();
        // Update the content with the id #c2479-singleview-1
      $(this).t3browser('update', "#c2479-singleview-1", $(this).attr("href") + "?type=28562");
    }
  );
});