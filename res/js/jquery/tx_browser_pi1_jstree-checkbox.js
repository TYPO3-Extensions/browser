/**
 *
 * Copyright (c) 2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 4.1.19
 */



$( document ).ready( function( )
{
  if( $( "###SELECTOR_01###" ).length )
  {
    $("###SELECTOR_01###").jstree({
      "plugins" : ["themes", "html_data", "checkbox", "ui", "cookies"]
    })
    .bind("select_node.jstree", function (event, data) { 
      // `data.rslt.obj` is the jquery extended node that was clicked
      alert(data.rslt.obj.attr("id"));
    })
  }
});

function generateHiddenFieldsForTree( treeId ) 
{
  var checked_ids = [];
  $( treeId ).jstree( "get_checked" , null, true ).each(function( )
  {
      checked_ids.push( this.id );
  });
  //setting to hidden field
  //document.getElementById('jsfields').value = checked_ids.join(",");
  value = checked_ids.join(",");
  alert( value );
}

$( function ( ) {
  $( "form" ).submit( function ( )
  {
    alert( "HALLO" );
    generateHiddenFieldsForTree( "###SELECTOR_01###" ); 
  });
});