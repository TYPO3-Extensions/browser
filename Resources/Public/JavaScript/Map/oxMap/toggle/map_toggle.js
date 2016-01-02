/**
 * @description map_toggle.js: toggle the map
 * @author (c) 2015-2016 - Dirk Wildt <http://wildt.at.die-netzmacher.de/>
 * @version 7.2.12
 * @since 6.0.8
 */

var labelSlidedown = "Show the map";
var labelSlideup = "Hide the map";
var lnDeSlidedown = "Karte einblenden";
var lnDeSlideup = "Karte ausblenden";

var htmlLang = $( 'html' ).attr( 'lang' ).substr( 0, 2 );

if( htmlLang === "de" ) {
  labelSlidedown = lnDeSlidedown;
  labelSlideup = lnDeSlideup;
}

$( document ).ready( function( )
{

  $( ".maptoggle button" ).html( labelSlideup );

  $( ".maptoggle" ).click( function() {
    if( $( ".mapview" ).length > 0 ) {
      if( $( ".mapview" ).css( "display" ) === "none" )
      {
        $( ".mapview" ).slideDown( 'slow' );
        $( ".maptoggle button" ).html( labelSlideup ).blur();
      }
      else
      {
        $( ".mapview" ).slideUp( 'slow' );
        $( ".maptoggle button" ).html( labelSlidedown ).blur();
      }
    }
    if( $( "#leafletmap" ).length > 0 ) {
      if( $( "#leafletmap" ).css( "display" ) === "none" )
      {
        $( "#leafletmap" ).slideDown( 'slow' );
        $( ".maptoggle button" ).html( labelSlideup ).blur();
      }
      else
      {
        $( "#leafletmap" ).slideUp( 'slow' );
        $( ".maptoggle button" ).html( labelSlidedown ).blur();
      }
    }
  } );

} );