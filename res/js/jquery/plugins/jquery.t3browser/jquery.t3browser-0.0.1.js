/**
 * jQuery t3browser Plugin 0.0.1
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
                return this.each(function() {        
                  // If options exist, lets merge them
                  // with our default settings
                  if ( options ) { 
                    $.extend( settings, options );
                  }
                });
              },
    show :    function( )
              {
              },
    hide :    function( )
              {
              },
    update :  function( html_element, url )
              {
                  // update():  replace the content of the given html element with the content 
                  //            of the requested url. The url may have an jQuery filter.
    var settings = {
      'location'         : 'top',
      'background-color' : 'blue'
    };
                return this.each(
                  function ( )
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
    alert(settings.location);
    $("#error").slideUp( 'fast' );
    // Testen ob html_element existiert, sonst Fehlermeldung
                    $( html_element ).load(
                      url,
                      function( response, status, xhr )
                      {
    //alert(status);
      if (status == "error") {
        var msg = "Sorry but there was an error: ";
        var msg1 = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>';
        var msg2 = '</strong>';
        var msg3 = '</p></div></div>';
        var prompt = "Did you configured a proper page object?\n Please check this URL: \n" + url;
        //var infPrompt = jQuery.t3browser.format( this.templates['uiInfo'], this.messages['hlpPageObjectLabel'], this.messages['hlpPageObjectPrompt']);
          //alert("'" + str + "'");
        $("#error").html(msg1 + xhr.statusText + ' (' + xhr.status + '): ' + msg2 + prompt + msg3);
        //$("#error").html(infPrompt);
    // Testen ob #error existiert, sonst alert oder add
    $("#error").slideDown( 'fast' );
        alert(msg + " | " + xhr.status + " | " + xhr.statusText);
      }
    //alert('2');
                          // Fade out the loader
                        $( "#tx-browser-pi1-loader" ).fadeOut( 500, function( )
                        {
                          $( this ).remove( );
                        });
                          // Remove the opacity of the html element
                        $( html_element ).removeClass( "opacity08" );
                        $( "input:submit, input:button, a.backbutton", ".tx-browser-pi1" ).button( );
    //alert('3');
                      }
                    );
                      // Send the AJAX request
                  }
                );
              },
                // update( )
    url_autoQm: function( url, param )
              {
                  // Concatenate the url and the param in dependence of a question mark.
                  // If url contains a question mark, param will added with ?param
                  // otrherwise with &param

                if(url.indexOf("?") >= 0)
                {
                  url = url + "&" + param;
                }
                if(!(url.indexOf("?") >= 0))
                {
                  url = url + "?" + param;
                }
                return url;
              },
                // url_autoQm
    format:   function(source, params) 
              {
                if ( arguments.length == 1 )
                {
                  return function() {
                    var args = $.makeArray(arguments);
                    args.unshift(source);
                    return $.t3browser.format.apply( this, args );
                  };
                }
                if ( arguments.length > 2 && params.constructor != Array  )
                {
                  params = $.makeArray(arguments).slice(1);
                }
                if ( params.constructor != Array )
                {
                  params = [ params ];
                }
                $.each(params, function(i, n)
                {
                  source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
                });
                return source;
              },
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



$.extend( $.fn.t3browser, 
{
  messages: {
    errError:           "Sorry but there was an error: ",
    hlpPageObjectLabel: "Do you have a proper page object?",
    hlpPageObjectPrmpt: "Please check the TYPO3 page object and the current typeNum.",
    hlpUrlLabel:        "Please check this URL manually",
    hlpUrlPrmpt:        "",
  },
          
  templates: {
    uiErr:  '<div class="ui-widget">' + 
              '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">' + 
                '<p>' + 
                  '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' +
                  '<strong>{0}</strong>' +
                  '{1}' +
                '</p>' +
              '</div>' +
            '</div>',
    uiInf:  '<div class="ui-widget">' + 
              '<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">'+ 
                '<p>' + 
                  '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' +
                  '<strong>{0}</strong>' +
                  '{1}' +
                '</p>' +
              '</div>' +
            '</div>',
  }
});
