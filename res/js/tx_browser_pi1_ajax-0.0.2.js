/*
 * AJAX methods for the TYPO3 extension Browser 
 * powered by jQuery (http://www.jquery.com)
 * powered by TYPO3 (http://www.typo3.org) 
 *
 * written by Frank Sander (http://www.wilder-jaeger.de)
 * Browser main development by Dirk Wildt (http://wildt.at.die-netzmacher.de)
 *
 * for more info visit http://typo3-browser-forum.de/
 * 
 * status: 28 Nov 2011
 * version: 0.0.2
 *
 */
 
 


var ajaxTimeout = 16000;
var typeNum = 0;

var debugColor = '#ee6600';

 

 
function initFirebug() {
// initialize some objects to avoid errors without Firebug
  if (!window.console || !console.firebug) {
    var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", 
"trace", "profile", "profileEnd"];
 
    window.console = {};
    for (var i = 0; i < names.length; i++) {
      window.console[names[i]] = function(){};
    }
  }
}
  
  
  
  
function setFocusTo(obj, pObj) {
// sets focus to changed objects for screen readers (for accessibility)
  if (!pObj.hasClass('nofocus')) {
    $('[tabindex=-1').removeAttr('tabindex');
    obj.attr('tabindex', -1);
    obj.focus();
  }
} 




function showAjaxError(obj, error) {
// displays ajax errors in the target object
  var errormsg = lang_ajaxErrorMsg[obj.closest('.ajax').attr('lang')];
  obj.html("\n\t<p class=\"ajaxerror\">\n\t\t" + errormsg + "<br />\n\t\t" + error + "\n\t</p>");
  obj.addClass('txbrowserpi1ajaxerror');
  obj.find('p').css({opacity: 0}).animate({opacity: 1}, 600);
  $('.txbrowserpi1loader').fadeOut(500, function() {
    $(this).remove();
  });
}




function insertParam(url, key, value) {
// inserts a parameter to a datastring query
  key = escape(key); 
  value = escape(value);
  var kvp = url.substr(0).split('&');
  url = '';
  if (kvp == '') {
    url = key + '=' + value;
  }
  else {
    var i = kvp.length; 
    var x = []; 
    while (i--) {
      x = kvp[i].split('=');
      if (x[0] == key) {
        x[1] = value;
        kvp[i] = x.join('=');
        break;
            }
        }
    if (i < 0) { 
      kvp[kvp.length] = [key, value].join('='); 
    }
    url = kvp.join('&');
    }
    return url;
}




function ajaxifyResetButton(pObj) {
  //var pObj;
  if (pObj.hasClass('debugjss')) {
    pObj.find('.searchbox .reset').css('border', '2px solid ' + debugColor);
  }
  pObj.find('.searchbox .reset').removeAttr('onclick').unbind('click').click(function() { 
    var targetObj = pObj.find('.searchbox');
    targetObj.addClass('loading');
    targetObj.prepend("\t<div class='txbrowserpi1loader'></div>\n");
    targetObj.find('.txbrowserpi1loader').fadeOut(0).fadeIn(150);
    var baseUrl = $('base').attr('href');
    var href = $(this).attr('href');
    if (!(href > '')) {
      href = $(this).closest('form').attr('action');
    }
    var url = baseUrl + href;
    var dataString = 'type=' + typeNum + '&tx_browser_pi1[segment]=searchform';
    $.ajax({
      context: pObj,
      url: url,
      dataType: 'html',
      data: dataString,        
      timeout: ajaxTimeout,
      success: function(d, s) {
        var pObj = $(this);
        // taking care of dynamically added single views (.byjs)
        pObj.find('.searchbox').add('.listarea', pObj).add('.byjs', pObj).wrapAll('<div class="browser_ajax_temp" />');
        pObj.find('.singleview').not('.byjs').empty();
                                    
        var targetObj = $(this).find('.browser_ajax_temp');
        pObj.find('.searchbox').removeClass('loading');
        $(this).find('.txbrowserpi1loader').fadeOut(500, function() {
          $(this).remove();
        });
        if (pObj.hasClass('ajaxltcollapse')) {
          targetObj.slideUp(300, function() {
            targetObj.empty().html(d);
            $(this).slideUp(1, function() {
              ajaxifySearchBox(pObj);
              ajaxifyList(pObj);
              $(this).slideDown(300, function() {
                pObj.find('.searchbox').unwrap();
                setFocusTo(pObj.find('.searchbox'), pObj);
              });
            });
          });
        }
        else {
          // no transition
          targetObj.empty().html(d);
          ajaxifySearchBox(pObj);
          ajaxifyList(pObj);  
          pObj.find('.searchbox').unwrap();
          setFocusTo(pObj.find('.searchbox'), pObj);                            
        }        
        // re-initialize single view if needed
        if (pObj.hasClass('ajax_single')) {
          if (pObj.find('.singleview').length < 1) {
            pObj.append("<div class=\"singleview byjs\">\n\t</div>\n");
          }
        }
      },
      error: function(req, error) {
        showAjaxError($(this).find('.listarea'), error);
      }
    });
    return false;
  });

}




function ajaxifySearchFormSubmit(pObj) {
  //var pObj;
  var searchform = pObj.find('.searchbox form');
  if (pObj.hasClass('debugjss')) {
    searchform.find(':submit').css('border', '2px solid ' + debugColor);
  }
  if (pObj.hasClass('hidesubmit')) {
    searchform.find(':submit').hide();
  }
  searchform.submit( function () { 
    var listarea = pObj.find('.listarea');
    var baseUrl = $('base').attr('href');
    var action = baseUrl + $(this).attr('action');
    var dataString = insertParam($(this).serialize(), 'tx_browser_pi1[segment]', 'list');
    dataString = insertParam(dataString, 'type', typeNum);
    listarea.addClass('loading');
    listarea.prepend("\t<div class='txbrowserpi1loader'></div>\n");
    listarea.find('.txbrowserpi1loader').fadeOut(0).fadeIn(200);
    $.ajax({
      context: pObj,
      type: 'GET',
      url: action,
      data: dataString,
      dataType: 'html',
      timeout: ajaxTimeout,
      success: function(d, s) {
        var pObj = $(this);
        var targetObj = $(this).find('.listarea');
        targetObj.removeClass('loading');        
        targetObj.find('.txbrowserpi1loader').fadeOut(500, function() {
          $(this).remove();
        });
        if (pObj.hasClass('ajaxltcollapse')) {
          targetObj.slideUp(300, function() {
            pObj.find('.listarea').replaceWith(d).queue( function () {
              $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
            });
            pObj.find('.listarea').slideUp(1, function() {
              ajaxifyList(pObj);  
              setFocusTo(pObj.find('.listarea'), pObj);
              $(this).slideDown(300);
            });                          
          });
        }
        else {
          // no transition
          targetObj.replaceWith(d).queue( function () {
            $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
          });
          ajaxifyList(pObj);  
        }                            
      },
      error: function(req, error) {
        showAjaxError($(this).find('.listarea'), error);
      }
    });
    return false;
  });
}




function ajaxifyDynamicFilters(pObj) {
  if (pObj.hasClass('dynamicFilters')) {
    var searchform = pObj.find('.searchbox form');
        
    if (pObj.hasClass('debugjss')) {
      searchform.find('.onchange').css('border', '2px solid ' + debugColor);
    }
    searchform.find('.onchange').change( function () { 
      var baseUrl = $('base').attr('href');
      var action = baseUrl + searchform.attr('action');
      var dataString = insertParam(searchform.serialize(), 'tx_browser_pi1[segment]','searchform');
      dataString = insertParam(dataString, 'type', typeNum);
      searchform.addClass('loading');
      searchform.prepend("\t<div class='txbrowserpi1loader'></div>\n");
      searchform.find('.txbrowserpi1loader').fadeOut(0).fadeIn(150);
      searchform.find(':input').add(':checkbox').add(':radio').add('button').attr("disabled", "disabled");
      $.ajax({
        context: pObj,
        url: action,
        dataType: 'html',
        data: dataString,        
        timeout: ajaxTimeout,
        success: function(d, s) {
          var pObj = $(this);          
		  $(this).find('.searchbox').add('.listarea', pObj).add('.listarea', pObj).wrapAll('<div class="browser_ajax_temp" />');
          pObj.find('.searchbox').removeClass('loading');    
          $(this).find('.txbrowserpi1loader').fadeOut(500, function() {
            $(this).remove();
          });
          // Removing of disabled attribute needed due to a bug in Firefox 3.6: 
          pObj.find('.searchbox form').find(':input').add(':checkbox').add(':radio').add('button').removeAttr("disabled");
          if (pObj.hasClass('ajaxltcollapse')) {
            var listarea = pObj.find('.listarea');
            listarea.slideUp(300, function() {
              pObj.find('.browser_ajax_temp').replaceWith(d).queue( function () {
                $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
              });
              pObj.find('.listarea').slideUp(1, function() {
                ajaxifySearchBox(pObj);
                ajaxifyList(pObj);
                $(this).slideDown(300);
              });
            });
          }
          else {
            // no transition
            pObj.find('.browser_ajax_temp').replaceWith(d).queue( function () {
              $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
            });
            ajaxifySearchBox(pObj);
            ajaxifyList(pObj);  
            setFocusTo(pObj.find('.searchbox'), pObj);                            
          }
        },
        error: function(req, error) {
          showAjaxError($(this).find('.listarea'), error);
        }
      });
    });
  }
}




function ajaxifySingleLinks(pObj) {
  if (pObj.hasClass('debugjss')) {
    pObj.find('.listarea .linktosingle').css('color', debugColor);
  }
  pObj.find('.listarea .linktosingle').click( function() { 
    var targetObj = pObj.find('.singleview');
    targetObj.addClass('loading');
    targetObj.prepend("\t<div class='txbrowserpi1loader'></div>\n");
    targetObj.find('.txbrowserpi1loader').fadeIn(150);
    var baseUrl = $('base').attr('href');
    var url = baseUrl + $(this).attr('href');
    var dataString = 'type=' + typeNum + '&tx_browser_pi1[segment]=single';
    $.ajax({
      context: pObj,
      url: url,
      dataType: 'html',
      data: dataString,        
      timeout: ajaxTimeout,
      success: function(d, s) {
        var pObj = $(this);
        var lang = pObj.attr('lang');
        targetObj = $(this).find('.singleview');
        targetObj.removeClass('loading');
        targetObj.find('.txbrowserpi1loader').fadeOut(500, function() {
          $(this).remove();
        });
        if (pObj.hasClass('ajaxltcollapse')) {
          if (pObj.hasClass('ajaxlossingle') || pObj.hasClass('ajaxloslistCollapsableAndSingle')) {
            var searchbox = $(this).find('.searchbox');
            searchbox.stop().slideUp(150);
            $(this).find('.listarea').stop().slideUp(300, function() {
              pObj.find('.returntolist').remove();
              $(this).after("\n\t<a class=\"returntolist\">" + lang_returnToList[lang] + "</a>\n");
              pObj.find('.returntolist').click( function() {
                pObj.find('.listarea').stop().slideDown(300);
                searchbox.stop().slideDown(150);
                $(this).fadeOut(100, function() {
                  $(this).remove();
                });
                if(!(pObj.hasClass('ajaxloslistCollapsableAndSingle'))) {
                  pObj.find('.singleview').stop().slideUp(150, function() {
                    $(this).empty();
                  });
                }
                return false;
              });
            });
          }
          targetObj.slideUp(300, function() {
            $(this).replaceWith(d).queue( function () {
              $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
            });
            pObj.find('.singleview').slideUp(1, function() {
              $(this).slideDown(300);
              setFocusTo($(this), pObj);
            });                            
          });
        }
        else {
          // no transition
          targetObj.replaceWith(d).queue( function () {
            $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
          });
          setFocusTo(pObj.find('.listarea'), pObj);  
        }                                    
      },
      error: function(req, error) {
        showAjaxError($(this).find('.listarea'), error);
      }
    });
    return false;
  });
}




function ajaxifyListViewLinks(pObj) {
  if (pObj.hasClass('debugjss')) {
    pObj.find('.listarea')
    .find('a')
    .not('.listview a')
    .add('.listview .browsebox a')
    .add('.ajaxifyme', pObj)
    .css('color', debugColor);
  }
  pObj.find('.listarea')
  .find('a')
  .not('.listview a')
  .add('.listview .browsebox a')
  .add('.ajaxifyme', pObj)
  .click( function() { 
    var targetObj = pObj.find('.listarea');
    targetObj.addClass('loading');
    targetObj.prepend("\t<div class='txbrowserpi1loader'></div>\n");
    targetObj.find('.txbrowserpi1loader').fadeOut(0).fadeIn(800);
    var baseUrl = $('base').attr('href');
    var url = baseUrl+$(this).attr('href');
    var dataString = 'type=' + typeNum + '&tx_browser_pi1[segment]=list';
    // send request
    $.ajax({
      context: pObj,
      url: url,
      dataType: 'html',
      data: dataString,        
      timeout: ajaxTimeout,
      success: function(d, s) {
        var pObj = $(this);
        var targetObj = $(this).find('.listarea');
        targetObj.removeClass('loading');
        targetObj.find('.txbrowserpi1loader').fadeOut(500, function() {
          $(this).remove();
        });
        if (pObj.hasClass('ajaxltcollapse')) {
          targetObj.slideUp(300, function() {
            pObj.find('.listarea').replaceWith(d).queue( function () {
              $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
            });
            pObj.find('.listarea').slideUp(1, function() {
              ajaxifyList(pObj);  
              setFocusTo(pObj.find('.listarea'), pObj);
              $(this).slideDown(300);
            });                          
          });
        }
        else {
          // no transition
          targetObj.replaceWith(d).queue( function () {
            $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
          });
          ajaxifyList(pObj);
          setFocusTo(pObj.find('.listarea'), pObj);  
        }                            
      },
      error: function(req, error) {
        showAjaxError($(this).find('.listarea'), error);
      }
    });
    return false;
  });
}




function ajaxifyList(pObj) {
  ajaxifyListViewLinks(pObj);  
  if (window.initOrderBy) { initOrderBy(pObj); }  
    
  if (pObj.hasClass('ajax_single')) {
    ajaxifySingleLinks(pObj);
  }
}




function ajaxifySearchBox(pObj) {
  ajaxifyResetButton(pObj);
  ajaxifySearchFormSubmit(pObj);
  ajaxifyDynamicFilters(pObj);
}




// =========================================================
 
 
 

function ajaxifyOrderBy(pObj) {
  var orderByForm = pObj.find('.selectboxorderby');
  if (pObj.hasClass('debugjss')) {
    orderByForm.find('select').css('border', '2px solid ' + debugColor);
  }
  orderByForm.removeAttr('onSubmit').unbind('submit').submit( function () { 
    var listarea = pObj.find('.listarea');
    var baseUrl = $('base').attr('href');
    var action = baseUrl + $(this).attr('action');
    var dataString = insertParam($(this).serialize(), 'tx_browser_pi1[segment]', 'list');
    dataString = insertParam(dataString, 'type', typeNum);
    listarea.addClass('loading');
    listarea.prepend("\t<div class='txbrowserpi1loader'></div>\n");
    listarea.find('.txbrowserpi1loader').fadeOut(0).fadeIn(150);
    $.ajax({
      context: pObj,
      type: 'GET',
      url: action,
      data: dataString,
      dataType: 'html',
      timeout: ajaxTimeout,
      success: function(d, s) {
        var pObj=$(this);
        var targetObj = $(this).find('.listarea');
        targetObj.removeClass('loading');
        targetObj.find('.txbrowserpi1loader').fadeOut(500, function() {
          $(this).remove();
        });
        if (pObj.hasClass('ajaxltcollapse')) {
          targetObj.slideUp(300, function() {
            pObj.find('.listarea').replaceWith(d).queue( function () {
              $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
            });
            pObj.find('.listarea').slideUp(1, function() {
              ajaxifyList(pObj);  
              setFocusTo(pObj.find('.listarea'), pObj);
              $(this).slideDown(300);
            });                          
          });
        }
        else {
          // no transition
          pObj.find('.listarea').replaceWith(d).queue( function () {
            $( "button, input:submit, input:button, a.backbutton, div.iconbutton", ".tx-browser-pi1" ).button( );
          });
          ajaxifyList(pObj);  
        }                            
      },
      error: function(req, error) {
        showAjaxError($(this).find('.listarea'), error);
      }
    });
    return false;
  });  
}




// =========================================================




this.setup_browserAJAX = function() {  
  // only for ajaxified browsers content objects:
  $('.ajax').each( function(i) {
    if ($(this).hasClass('debugjss')) {
      var debugLang = $(this).attr('lang');
      console.info('[tx_browser_pi1 [' + i + ']] AJAX initialisation started\n\tdetected language: ' + debugLang + '\n\ttime out settings: ' 
+ ajaxTimeout + ' ms');     
      console.time('[tx_browser_pi1 [' + i + ']] AJAX initialisation');     
    }
    var browser = $(this);
    
    // initialize single view if needed
    if ($(this).hasClass('ajax_single')) {
      if ($(this).find('.singleview').length < 1) {
        $(this).append("<div class=\"singleview byjs\">\n\t</div>\n");
      }
    }
    // link processing
    ajaxifyList(browser);
    ajaxifySearchBox(browser);

    if ($(this).hasClass('debugjss')) {
      console.timeEnd('[tx_browser_pi1 ['  + i + ']] AJAX initialisation');     
    }

  }); 
};
 



// =========================================================
 
 
 
 
// starting the script on page load
$(document).ready( function() {
  initFirebug();
  setup_browserAJAX();
});

