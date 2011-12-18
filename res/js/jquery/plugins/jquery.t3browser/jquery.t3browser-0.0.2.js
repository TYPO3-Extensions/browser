/**
 * jQuery t3browser Plugin 0.0.2
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

  var settings = {
    messages: {
      errMissingTagPropertyLabel: "Tag is missing:",
      errMissingTagPropertyPrmpt: "A HTML tag with an attribute {0} is missing. AJAX can't work proper!",
      errServerErrorPrmpt:        "This is a message from the server. Maybe the server has some problems in general. " +
                                  "If the server delivers content, you will see the content below this prompts.",
      hlpForumLabel:              "Interactive support:",
      hlpForumPrmpt:              "If you need interactive help, please visit the TYPO3-Browser-Forum at " +
                                  "<a href=\"http://typo3-browser-forum.de/\">typo3-browser-forum.de</a>. Posts arewelcome " +
                                  "in English and German.",
      hlpMissingTagPropertyLabel: "Be aware of a proper HTML template:",
      hlpMissingTagPropertyPrmpt: "Please add something like <div id=\"{0}\">...</div> to your template.",
      hlpPageObjectLabel:         "You can check TYPO3:",
      hlpPageObjectPrmpt:         "Is there a proper page object? Is there a proper typeNum?",
      //hlpAjaxConflictLabel: "Maybe there is a conflict:",
      //hlpAjaxConflictPrmpt: "Don't use AJAX in the single view. See flexform/plugin sheet [jQuery] field [AJAX].",
      hlpUrlLabel:                "You can check the URL manually:",
      hlpUrlPrmpt:                "Click on {0}",
      hlpUrlSelectorLabel:        "Be aware of the jQuery selector:",
      hlpUrlSelectorPrmpt:        "The request takes content into account only if this selector gets a result: {0}",
      hlpGetRidOfLabel:           "Get rid of this messages?",
      hlpGetRidOfPrmpt:           "Deactivate the jQuery plugin t3browser. But you won't have any AJAX support."
    },
    templates: {
      uiErr : '<div class="ui-widget">' +
                '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">' +
                  '<p>' +
                    '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' +
                    '<strong>{0}</strong> ' +
                    '{1}' +
                  '</p>' +
                '</div>' +
              '</div>',
      uiInf : '<div class="ui-widget">' +
                '<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">' +
                  '<p>' +
                    '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' +
                    '<strong>{0}</strong> ' +
                    '{1}' +
                  '</p>' +
                '</div>' +
              '</div>'
    }
  };

  var methods = {
    init        : function( settings_ )
                  {
                    return this.each( function( ) {
                        // If settings_ exist, lets merge them with our default settings
                      if ( settings_ ) {
                        $.extend( settings, settings_ );
                      }
                    });
                  },
    appendHidden  : function( html_element, url, plugin_id, plugin_id_wi_selector )
                  {
                      // appendHidden():  append the content of the given html element with the content
                      //                  of the requested url to the given previous html_element.
                      //                  The content is the content of the html element with selector.

                    return this.each( function ( )
                    {
                        // ERROR html_element is missing. Don't use AJAX but forward
                      if( ! $( plugin_id ).length ) {
                        prompt = format( settings.messages.errMissingTagPropertyPrmpt, plugin_id);
                        alert( settings.messages.errMissingTagPropertyLabel + " " + prompt );
                        prompt = format( settings.messages.hlpMissingTagPropertyPrmpt, plugin_id);
                        alert( settings.messages.hlpMissingTagPropertyLabel + " " + prompt );
                        //alert(settings.messages.hlpAjaxConflictLabel + " " + settings.messages.hlpAjaxConflictPrmpt );
                        fq_url = window.location.protocol + "//" + window.location.host + "/" + url;
                        window.location.href = fq_url;
                        return;
                      }
                        // ERROR plugin_id is missing. Don't use AJAX but forward

                        // Fade out the update prompt
                      $("#update-prompt:visible").slideUp( 'fast' );
                      $("#update-prompt div").remove( );

                        // Cover the html_element with the loading *.gif
                      cover_wi_loader( html_element );

                        // Send the AJAX request
                        // Append to the prev_html element the delivered data
                      var url_wi_selector = url + " " + plugin_id_wi_selector;
                      $( plugin_id ).append( "<div id=\"tmp-container\"></div>" );
                      $( "#tmp-container" ).load(url_wi_selector, function( response, status, xhr )
                      {
                          // ERROR server has an error and has send a message
                        if (status == "error")
                        {
                            // Add error messages and helpful informations to the update prompt
                          err_prompt( "#update-prompt", xhr.statusText + " (" + xhr.status + "):", settings.messages.errServerErrorPrmpt);
                          inf_prompt( "#update-prompt", settings.messages.hlpPageObjectLabel, settings.messages.hlpPageObjectPrmpt );
                          fq_url = window.location.protocol + "//" + window.location.host + "/" + url;
                          a_fq_url = '<a href="' + fq_url + '">' + fq_url + '</a>';
                          prompt = format( settings.messages.hlpUrlPrmpt, a_fq_url);
                          inf_prompt( "#update-prompt", settings.messages.hlpUrlLabel, prompt );
                          prompt = format( settings.messages.hlpUrlSelectorPrmpt, plugin_id_wi_selector);
                          //inf_prompt( "#update-prompt", settings.messages.hlpUrlSelectorLabel, prompt );
                          inf_prompt( "#update-prompt", settings.messages.hlpForumLabel, settings.messages.hlpForumPrmpt );
                          inf_prompt( "#update-prompt", settings.messages.hlpGetRidOfLabel, settings.messages.hlpGetRidOfPrmpt );
                            // Add error messages and helpful informations to the update prompt

                            // Fade out the loading *.gif, initiate buttons again
                          clean_up( html_element );
                            // Fade in the update prompt
                          $( "#update-prompt:hidden" ).slideDown( 'fast' );
                          $( "#update-prompt" ).append( response );
                          return;
                        }
                          // ERROR server has an error and has send a message


                        if( ! $( "#tmp-container div" ).length ) {
                          alert( "ERROR: Content is empty!\n" +
                            "Please check the link manually:\n" +
                            url + "\n" +
                            "\n" +
                            "Reason can be:\n" +
                            "- The TYPO3 colPos of your content isn't 0.\n" +
                            "\n" +
                            "Please take care of a proper TypoScript."
                          );
                          return;
                        }

                        var id_of_loaded_content = "#" + $( "#tmp-container div" ).attr( "id" )
                        $( id_of_loaded_content ).unwrap( );
                        $( id_of_loaded_content ).hide( );

                          // Fade out the loading *.gif, initiate buttons again
                        clean_up( html_element );
                      });
                        // Send the AJAX request
                    });

                      // Cover the current html element with the loader *.gif
                    function cover_wi_loader( html_element ) {
                        // Add an opacity to the html element
                      $( html_element ).addClass( "opacity08" );

                        // Cover the html element with a loading gif
                      $( html_element ).prepend( "\t<div id='tx-browser-pi1-loader'></div>\n" );
                        // Get the size of the html element
                      var heightWiPx      = $( html_element ).css( "height" );
                      var widthWiPx       = $( html_element ).css( "width" );
                      var marginBottomPx  = "-" + $( html_element ).css( "width" );
                        // Set the loader to the size of the html element
                      $( "#tx-browser-pi1-loader" ).css( "height",        heightWiPx      );
                      $( "#tx-browser-pi1-loader" ).css( "width",         widthWiPx       );
                      $( "#tx-browser-pi1-loader" ).css( "margin-bottom", marginBottomPx  );
                        // Fade in the loader
                      $( "#tx-browser-pi1-loader" ).fadeIn( 150 );
                        // Cover the html element with a loading gif
                    };
                      // Cover the current html element with the loader *.gif

                      // Fade out the loading *.gif, initiate buttons again
                    function clean_up( html_element ) {
                        // Fade out the loading *.gif
                      $( "#tx-browser-pi1-loader" ).fadeOut( 500, function( )
                      {
                        $( this ).remove( );
                      });
                        // Remove the opacity of the html element
                      $( html_element ).removeClass( "opacity08" );
                        // Initiate the ui button layout again
                      $( "input:submit, input:button, a.backbutton", ".tx-browser-pi1" ).button( );
                    };
                      // Fade out the loading *.gif, initiate buttons again

                      // Prompt errors
                    function err_prompt( selector, label, prompt ) {
                      if( !$( "#update-prompt" ).length ) {
                        alert( label + " " + prompt);
                        return;
                      }
                      element = format( settings.templates.uiErr, label, prompt);
                      $( selector ).append( element );
                    };
                      // Prompt errors

                      // Prompt informations
                    function inf_prompt( selector, label, prompt ) {
                      if( !$( "#update-prompt" ).length ) {
                        alert( label + " " + prompt);
                        return;
                      }
                      element = format( settings.templates.uiInf, label, prompt);
                      $( selector ).append( element );
                    };
                      // Prompt informations

                      // Replace vars in the source with the given params
                    function format( source, params ) {
                        // ERROR with source
                      if( typeof source == "undefined" )
                      {
                        source =  'ERROR in jquery.t3browser-0.0.2.js: source is undefined. It seems that there is a ' +
                                  'problem with a not defined variable. Function format( source, params ).';
                      }
                        // ERROR with params
                      if( typeof params == "undefined" )
                      {
                        params = new Array();
                        params[0] = 'ERROR in jquery.t3browser-0.0.2.js:';
                        params[1] = 'params are undefined. It seems that there is a problem with a not defined variable. ' +
                                    'Function format( source, params ). Please check settings { ... }.';
                      }
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
                    };
                      // Replace vars in the source with the given params
                  },
                    // appendHidden( )
    runEffect   : function ( element_out, element_in, selectedEffect )
                  {
                    return this.each( function ( )
                    {
                        // ERROR html_element is missing. Don't use AJAX but forward
                      if( !$( element_out ).length ) {
                        alert( element_out + " doesn\'t exist." );
                        return;
                      }
                        // ERROR element_outis missing. Don't use AJAX but forward

                        // get effect type from
                      //var selectedEffect = $( "#effectTypes" ).val();

                        // most effect types need no options passed by default
                      var options = { };
                        // some effects have required parameters
                      if ( selectedEffect === "scale" )
                      {
                        options = { percent: 0 };
                      }
                      else if ( selectedEffect === "size" )
                      {
                        options = {
                                    to : {
                                      width   : 200,
                                      height  : 60
                                    }
                                  };
                      }
                      else if ( selectedEffect === "slide" )
                      {
                        options = {
                                    mode      : 'hide'
//                                    direction : 'right'
                                  };
                      }
                      else if ( selectedEffect === "transfer" )
                      {
                        options = {
                                    to        : "#button",
                                    className : "ui-effects-transfer"
                                  };
                      }

                        // run the effect
                      $( element_out ).effect( selectedEffect, options, 500, callback( selectedEffect ) );
                    });

                    // callback function to bring a hidden box back
                    function callback( selectedEffect ) {
                      setTimeout(function() {
                        if ( selectedEffect === "slide" )
                        {
                          options = {
                                      mode      : 'show',
                                      direction : 'right'
                                    };
                          $( element_in ).effect( selectedEffect, options, 500 );
                        }
                        else
                        {
                            $( element_in ).removeAttr( "style" ).hide().fadeIn();
                        }
                      }, 1 );
                    };
                  },
    update      : function( html_element, url, html_element_wi_selector )
                  {
                      // update():  replace the content of the given html element with the content
                      //            of the requested url. The content is the content of the html element with selector.

                    return this.each( function ( )
                    {
                        // ERROR html_element is missing. Don't use AJAX but forward
                      if( !$( html_element ).length ) {
                        prompt = format( settings.messages.errMissingTagPropertyPrmpt, html_element);
                        alert( settings.messages.errMissingTagPropertyLabel + " " + prompt );
                        prompt = format( settings.messages.hlpMissingTagPropertyPrmpt, html_element);
                        alert( settings.messages.hlpMissingTagPropertyLabel + " " + prompt );
                        //alert(settings.messages.hlpAjaxConflictLabel + " " + settings.messages.hlpAjaxConflictPrmpt );
                        fq_url = window.location.protocol + "//" + window.location.host + "/" + url;
                        window.location.href = fq_url;
                        return;
                      }
                        // ERROR html_elementis missing. Don't use AJAX but forward

                        // Fade out the update prompt
                      $("#update-prompt:visible").slideUp( 'fast' );
                      $("#update-prompt div").remove( );

                        // Cover the html_element with the loading *.gif
                      cover_wi_loader( html_element );

                        // Send the AJAX request
                        // Replace the content of the html element with the delivered data
                      var url_wi_selector = url + " " + html_element_wi_selector;
                      $( html_element ).load(url_wi_selector, function( response, status, xhr )
                      {
                          // ERROR server has an error and has send a message
                        if (status == "error")
                        {
                            // Add error messages and helpful informations to the update prompt
                          err_prompt( "#update-prompt", xhr.statusText + " (" + xhr.status + "):", settings.messages.errServerErrorPrmpt);
                          inf_prompt( "#update-prompt", settings.messages.hlpPageObjectLabel, settings.messages.hlpPageObjectPrmpt );
                          fq_url = window.location.protocol + "//" + window.location.host + "/" + url;
                          a_fq_url = '<a href="' + fq_url + '">' + fq_url + '</a>';
                          prompt = format( settings.messages.hlpUrlPrmpt, a_fq_url);
                          inf_prompt( "#update-prompt", settings.messages.hlpUrlLabel, prompt );
                          prompt = format( settings.messages.hlpUrlSelectorPrmpt, html_element_wi_selector);
                          //inf_prompt( "#update-prompt", settings.messages.hlpUrlSelectorLabel, prompt );
                          inf_prompt( "#update-prompt", settings.messages.hlpForumLabel, settings.messages.hlpForumPrmpt );
                          inf_prompt( "#update-prompt", settings.messages.hlpGetRidOfLabel, settings.messages.hlpGetRidOfPrmpt );
                            // Add error messages and helpful informations to the update prompt

                            // Fade out the loading *.gif, initiate buttons again
                          clean_up( html_element );
                            // Fade in the update prompt
                          $( "#update-prompt:hidden" ).slideDown( 'fast' );
                          $( "#update-prompt" ).append( response );
                          return;
                        }
                          // ERROR server has an error and has send a message

                          // Fade out the loading *.gif, initiate buttons again
                        clean_up( html_element );
                      });
                        // Send the AJAX request
                    });

                      // Cover the current html element with the loader *.gif
                    function cover_wi_loader( html_element ) {
                        // Add an opacity to the html element
                      $( html_element ).addClass( "opacity08" );

                        // Cover the html element with a loading gif
                      $( html_element ).prepend( "\t<div id='tx-browser-pi1-loader'></div>\n" );
                        // Get the size of the html element
                      var heightWiPx      = $( html_element ).css( "height" );
                      var widthWiPx       = $( html_element ).css( "width" );
                      var marginBottomPx  = "-" + $( html_element ).css( "width" );
                        // Set the loader to the size of the html element
                      $( "#tx-browser-pi1-loader" ).css( "height",        heightWiPx      );
                      $( "#tx-browser-pi1-loader" ).css( "width",         widthWiPx       );
                      $( "#tx-browser-pi1-loader" ).css( "margin-bottom", marginBottomPx  );
                        // Fade in the loader
                      $( "#tx-browser-pi1-loader" ).fadeIn( 150 );
                        // Cover the html element with a loading gif
                    };
                      // Cover the current html element with the loader *.gif

                      // Fade out the loading *.gif, initiate buttons again
                    function clean_up( html_element ) {
                        // Fade out the loading *.gif
                      $( "#tx-browser-pi1-loader" ).fadeOut( 500, function( )
                      {
                        $( this ).remove( );
                      });
                        // Remove the opacity of the html element
                      $( html_element ).removeClass( "opacity08" );
                        // Initiate the ui button layout again
                      $( "input:submit, input:button, a.backbutton", ".tx-browser-pi1" ).button( );
                    };
                      // Fade out the loading *.gif, initiate buttons again

                      // Prompt errors
                    function err_prompt( selector, label, prompt ) {
                      if( !$( "#update-prompt" ).length ) {
                        alert( label + " " + prompt);
                        return;
                      }
                      element = format( settings.templates.uiErr, label, prompt);
                      $( selector ).append( element );
                    };
                      // Prompt errors

                      // Prompt informations
                    function inf_prompt( selector, label, prompt ) {
                      if( !$( "#update-prompt" ).length ) {
                        alert( label + " " + prompt);
                        return;
                      }
                      element = format( settings.templates.uiInf, label, prompt);
                      $( selector ).append( element );
                    };
                      // Prompt informations

                      // Replace vars in the source with the given params
                    function format( source, params ) {
                        // ERROR with source
                      if( typeof source == "undefined" )
                      {
                        source =  'ERROR in jquery.t3browser-0.0.2.js: source is undefined. It seems that there is ' +
                                  'a problem with a not defined variable. Function format( source, params ).';
                      }
                        // ERROR with params
                      if( typeof params == "undefined" )
                      {
                        params = new Array();
                        params[0] = 'ERROR in jquery.t3browser-0.0.2.js:';
                        params[1] = 'params are undefined. It seems that there is a problem with a not defined variable. ' +
                                    'Function format( source, params ). Please check settings { ... }.';
                      }
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
                    };
                      // Replace vars in the source with the given params
                  },
                    // update( )
    url_autoQm  : function( url, param )
                  {
                      // Concatenate the url and the param in dependence of a question mark.
                      // If url contains a question mark, param will added with ?param
                      // otherwise with &param

                    if( typeof url == "undefined" )
                    {
                      url   = "";
                      param = "";
                    }
                    if( url.indexOf( "?" ) >= 0 )
                    {
                      url = url + "&" + param;
                    }
                    if( ! ( url.indexOf( "?" ) >= 0 ) )
                    {
                      url = url + "?" + param;
                    }
                    return url;
                  }
                    // url_autoQm
  };

  $.fn.t3browser = function( method )
  {
      // Method calling logic
      // See http://docs.jquery.com/Plugins/Authoring#Plugin_Methods

      // Return executed method
    if ( methods[method] )
    {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    }

      // Set default values
    if ( typeof method === "object" || ! method )
    {
      return methods.init.apply( this, arguments );
    }

      // Error: No proper method, no arguments
    $.error( "Method " +  method + " does not exist on jQuery.t3browser" );
      // Method calling logic
  };

})( jQuery );

