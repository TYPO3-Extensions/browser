/*
 * Javascript methods for the TYPO3 extension Browser 
 * powered by jQuery (http://www.jquery.com)
 * powered by TYPO3 (http://www.typo3.org) 
 *
 * written by Frank Sander (http://www.wilder-jaeger.de)
 * Browser main development by Dirk Wildt (http://wildt.at.die-netzmacher.de)
 *
 * for more info visit http://typo3-browser-forum.de/
 *
 * status: 19 Dec 2010
 */
 
 
 

function initOrderBy(pObj) {
// pObj ignored for the moment
  $('.tx-browser-pi1').each(function(i) {
    var browser = $(this);
    if (browser.find('.ajax').length > 0) {
      ajaxifyOrderBy(browser.find('.ajax'));
    }
    var orderByForm = browser.find('.selectboxorderby');
    orderByForm.find('select').not('.nochange').removeAttr('onchange').unbind('change').change(function () {
      $(this).closest('form').submit(); 
    });  
    // remove submit button:
    orderByForm.find('select').not('.nochange').closest('form').find(':submit').remove();
  });
}




// =========================================================
 
// starting the script on page load
$(document).ready(function() {
  initOrderBy();
});
