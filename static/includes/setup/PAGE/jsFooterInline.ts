page {

  jsFooterInline {
      // browser: $( document ).foundation();
    66666 = COA
    66666 {
      10 = TEXT
      10 {
        value (
$( document ).ready( function( )
{
  $.jstree._themes = "{$plugin.tx_browser_pi1.jQuery.plugin.jstree.pathToTheme}";
  if( $( "#{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_01}" ).length )
  {
    $("#{$plugin.tx_browser_pi1.jQuery.plugin.jstree.selector_01}").jstree({
      "checkbox" : {
        "override_ui" : true
      },
      "cookies" : {
        "save_loaded"   : "jstreeTreeview01_loaded",
        "save_opened"   : "jstreeTreeview01_opened",
        "save_selected" : "jstreeTreeview01_selected"
      },
      "themes" : {
        "theme" : "{$plugin.tx_browser_pi1.jQuery.plugin.jstree.theme}",
        "dots"  : {$plugin.tx_browser_pi1.jQuery.plugin.jstree.dots},
        "icons" : {$plugin.tx_browser_pi1.jQuery.plugin.jstree.icons}
      },
      "plugins" : [ "themes", "html_data", "cookies" ]
    });
  }
});
)
      }
    }
  }
}