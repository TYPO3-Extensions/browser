/**
 *
 * Copyright (c) 2011-2012 Dirk Wildt
 * http://wildt.at.die-netzmacher.de/
 *
 * Version 0.0.4
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

    //////////////////////////////////////////////////////////
    //
    // UI for buttons in areas with the class tx-browser-pi1

  //alert( jQuery.isFunction( $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( ) ) );
  //if( 1 == 0 ) {
    try {
      $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
    }
    catch( err )
    {
      // jQuery is compiled without button method. Don't worry!
    }
  //}
    // UI for buttons in areas with the class tx-browser-pi1



    //////////////////////////////////////////////////////////
    //
    // Record browser

    // User has clicked the record browser
  $( ".c###TT_CONTENT.UID###-record-browser" ).live(
    'click',
    function( e ) {
        // Don't execute the click
      e.preventDefault( );

      var url                       = $( this ).t3browser( 'url_autoQm', $( this ).attr( "href" ), "type=###TYPENUM_AJAX###" );
      var html_element              = "#c###TT_CONTENT.UID###-singleview-###MODE###";
      var html_element_wi_selector  = html_element + " > *";
        // RETURN selected id isn't part of the DOM
      if( ! $( "#c###TT_CONTENT.UID###-singleview-###MODE###" ).length )
      {
        alert( "ERROR: The selctor \"#c###TT_CONTENT.UID###-singleview-###MODE###\" isn't part of the DOM!");
        return;
      }
        // Update the content with the id #c###TT_CONTENT.UID###-###VIEW###view-###MODE###
      $( this ).t3browser( 'update', html_element, url, html_element_wi_selector );
    }
  );
    // User has clicked the record browser
    // Record browser



    //////////////////////////////////////////////////////////
    //
    // CSV export

    // User has clicked the CSV export button
    // Append a hidden field with TYPO3 page type for csv export
  $( "#c###TT_CONTENT.UID###-list-submit-csv-export-###MODE###" ).live(
    'click',
    function ( e )
    {
        // RETURN selected form with fieldset isn't part of the DOM
      if( ! $( "#c###TT_CONTENT.UID###-list-searchbox-form-###MODE### fieldset" ).length )
      {
          // Don't execute the click
        e.preventDefault( );
        alert( "ERROR: The selector \"#c###TT_CONTENT.UID###-list-searchbox-form-###MODE### fieldset\" isn't part of the DOM!");
        return;
      }
        // Append the TYPO3 typeNum of the csv export page object
      $( "#c###TT_CONTENT.UID###-list-searchbox-form-###MODE### fieldset" )
        .append(
          '<input id="c###TT_CONTENT.UID###-button-export-###MODE###" type="hidden" name="type" value="###TYPENUM_CSV###" />'
        );
      bool_sendAjaxRequest = false;
    }
  );
    // Append a hidden field with TYPO3 page type for csv export
    // Remove the hidden field after submitting the form
  $( "#c###TT_CONTENT.UID###-list-submit-csv-export-###MODE###" ).blur(
    function ( e ) 
    {
      $( "#c###TT_CONTENT.UID###-button-export-###MODE###" ).remove( )
      bool_sendAjaxRequest = true;
    }
  );
    // Remove the hidden field after submitting the form
    // User has clicked the CSV export button
    // CSV export



    //////////////////////////////////////////////////////////
    //
    // Mode sliding

    // Parameters for the slide effect
  var int_minutes   = 60 * 1000;
  var element_out   = ".c###TT_CONTENT.UID###-tx-browser-pi1-list:visible";
  var options_out   = {
                        mode      : 'hide',
                        direction : 'left'
                      };
  var options_in    = {
                        mode      : 'show',
                        direction : 'right'
                      };
    // Parameters for the slide effect

    // The user has clicked a tab
    // * Slide the current TYPO3 plugin out
    // * Slide the selected TYPO3 plugin in
  $( ".c###TT_CONTENT.UID###-list-tab" ).live(
    'click',
    function( e ) {

        // Get the mode
      var tab_id      = $( this ).attr( "id" );
      var tab_prefix  = "c###TT_CONTENT.UID###-list-tab-";
      var mode        = tab_id.substr(tab_prefix.length);
        // Get the mode

        // RETURN selected mode isn't part of the DOM
      if( ! $( "#c###TT_CONTENT.UID###-tx-browser-pi1-list-" + mode ).length )
      {
          // Do not alert! Click will executed without jQuery slide effect.
        return;
      }
        // RETURN selected mode isn't part of the DOM

        // Don't execute the click
      e.preventDefault( );

        // Slide
      var element_in = "#c###TT_CONTENT.UID###-tx-browser-pi1-list-" + mode;
      $( element_out ).effect( 'slide', options_out, 500 );
      setTimeout(function() {
        $( element_in ).effect( 'slide', options_in, 500 );
      }, 500 );
        // Slide
    }
  );
    // The user has clicked a tab

    // Load the content for the given mode, append it to the content of the browser plugin and hide it.
  function load_mode( mode )
  {
      // Load the content only, if it is the first time
    if( ! $( "#c###TT_CONTENT.UID###-tx-browser-pi1-list-" + mode ).length )
    {
      var html_element          = "#c###TT_CONTENT.UID###-list-tab-" + mode;
      var url                   = $( this )
                                    .t3browser( 'url_autoQm', $( "#c###TT_CONTENT.UID###-list-tab-" + mode + " a" )
                                    .attr( "href" ), "type=###TYPENUM_AJAX###" );
      var plugin_id             = "#c###TT_CONTENT.UID###";
      var plugin_id_wi_selector = plugin_id + " > *";
        // RETURN plugin_id isn't part of the DOM
      if( ! $( plugin_id ).length )
      {
        alert( "ERROR: \"" + plugin_id + "\" isn't part of the DOM!");
        return;
      }
        // Append the content of plugin_id_wi_selector as html_element at the bottom of the plugin_id 
      $( this ).t3browser( 'appendHidden', html_element, url, plugin_id, plugin_id_wi_selector );
    }
      // Load the content only, if it is the first time
  }
    // Load the content for the given mode, append it to the content of the browser plugin and hide it.

    // Load the content for all modes
  var int_seconds = 15 * 1000 ; // 15 seconds (load is asynchron! Time have to be proper in case of busy servers.
  ###LOAD_ALL_MODES###
    // Load the content for all modes
    // Mode sliding


});