plugin.tx_browser_pi1 {

  # cat=Browser - Templates - Single - Text 1*//102;         type=string;     label= Header*:(Obligate!) Field with the content for the header. I.e: tx_org_event.header
  templates.singleview.text.1.header       =
  # cat=Browser - Templates - Single - Text 1*//102;         type=string;     label= Header tag:HTML tag for the header. I.e: h1
  templates.singleview.text.1.headertag    = h1
  # cat=Browser - Templates - Single - Text 1*//110;         type=string;     label= Bodytext:Field with the content for the bodytext. I.e: tx_org_event.bodytext // tx_org_event.teaser_short
  templates.singleview.text.1.bodytext     =
  # cat=Browser - Templates - Single - Text 1*//others/999;  type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.singleview.text.1.links      = Click me!

}