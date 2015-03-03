plugin.tx_browser_pi1 {

  # cat=Browser - Templates - List - URL 5*//100;        type=string;  label= Key*:(Obligate!) Field with the key for controlling the URL type. I.e: tx_org_service.type
  templates.listview.url.5.key  =
  # cat=Browser - Templates - List - URL 5*//200;        type=string; label= Uid*:(Obligate!) Field with the uid of the current record. I.e: tx_org_service.uid
  templates.listview.url.5.record  =
  # cat=Browser - Templates - List - URL 5*//201;        type=string;  label= Page*:(Obligate!) Field with the link to the internal page. I.e: tx_org_service.page
  templates.listview.url.5.page  =
  # cat=Browser - Templates - List - URL 5*//202;        type=string;  label= URL*:(Obligate!) Field with the link to the external website. I.e: tx_org_service.url
  templates.listview.url.5.url  =
  # cat=Browser - Templates - List - URl 5*//203;        type=boolean;  label= Force absolute URL:If it's enabled, a absolute URL will rendered. An absolute URL in a newsletter is obligatory!
  templates.listview.url.5.forceAbsoluteUrl = 0
  # cat=Browser - Templates - List - URl 5*//204;        type=options[http,https];  label= Scheme:Scheme is used, if absolute Url is enabled.
  templates.listview.url.5.forceAbsoluteUrlScheme = http
  # cat=Browser - Templates - List - URl 5*//300;        type=string;  label= Alias for showUid:If the destination tables is using an alias for the showUid, you must enter it. I.e: jobUid
  templates.listview.url.5.showUid  = showUid
  # cat=Browser - Templates - List - URL 5*//300;        type=int+;    label= PID of single view:Page id of the single view. Leave it empty, if the list view and single have the same pid. I.e: 142
  templates.listview.url.5.singlePid  =
  # cat=Browser - Templates - List - URL 5*//others/999; type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.listview.url.5.links = Click me!

}