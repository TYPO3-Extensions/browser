/**
 * jQuery t3_browser Plugin 3.7.0
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
    init : 	function( options ) 
		{
		  // THIS
		},
    show : 	function( )
		{
		    // IS
		},
    hide : 	function( )
		{
		   // GOOD
		},
    update : 	function( id )
		{
		  //e.preventDefault();
		  $(id).slideUp("slow");
		  $(id).slideDown("slow");
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

//$('div').tooltip(); // calls the init method
//$('div').tooltip({  // calls the init method
//  foo : 'bar'
//});
//$('div').tooltip('hide'); // calls the hide method
//$('div').tooltip('update', 'This is the new tooltip content!'); // calls the update method

$(document).ready(function() {

  $(".c2479-record-browser").click(
    function(e) {
      e.preventDefault(); 
//      $(".c2479-record-browser").t3browser('update', "#c2479-singleview-1");
      $(this).t3browser('update', "#c2479-singleview-1");
    }
  );
});