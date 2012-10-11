
$( document ).ready( function( ) {
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
  //$.jstree._themes = "/jstree-read-only/themes/";
});

function generateHiddenFieldsForTree( ) 
{
  var checked_ids = [];
  var name  = "tx_browser_pi1[tx_greencars_manufacturer.title][]";

    // Append an input field for each selected <li>-item to the current form
  $( "#tx_greencars_manufacturer_title" ).jstree( "get_checked" , null, true ).each(function( )
  {
      // Get current record uid
    var thisId         = this.id;
    var thisIdSplitted = thisId.split( "_" );
    var recordUid      = thisIdSplitted[ thisIdSplitted.length - 1 ];

     // Append an input field with the record uid
    if( recordUid )
    {
      $( "form" ).append('<input type="hidden" name="' + name + '" value="' + recordUid + '" />');
    }
  });

}
$( function ( ) {
  $( "form" ).submit( function ( )
  {
    generateHiddenFieldsForTree( );
  });
});