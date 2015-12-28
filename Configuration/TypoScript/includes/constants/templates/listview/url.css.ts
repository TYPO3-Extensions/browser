plugin.tx_browser_pi1 {

  # cat=Browser - Templates - List - URL CSS//100;        type=string;  label= Link to the single view:CSS class for a link to a record (single view)
  templates.listview.url.css.record = linktorecord
  # cat=Browser - Templates - List - URL CSS//101;        type=string;  label= Link to a page:CSS class for a link to an internal page
  templates.listview.url.css.page   = linktopage
  # cat=Browser - Templates - List - URL CSS//102;        type=string;  label= Link to an URL:CSS class for a link to an external website
  templates.listview.url.css.url    = linktourl
  # cat=Browser - Templates - List - URL CSS//999; type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.listview.url.css.links = Click me!

}