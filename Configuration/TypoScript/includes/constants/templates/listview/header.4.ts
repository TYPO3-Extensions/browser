plugin.tx_browser_pi1 {

  # cat=Browser - Templates - List - Header 4*//101;        type=string;    label= Header:Field with the header for the list view. I.e: tx_org_service.teaser_title // tx_org_service.title
  templates.listview.header.4.field  =
  # cat=Browser - Templates - List - Header 4*//102;        type=options[div,h1,h2,h3,h4,h5,h6,p,span];  label= Tag:HTML tag. I.e: h2
  templates.listview.header.4.tag   = h2
  # cat=Browser - Templates - List - Header 4*//103;        type=string;    label= Crop header:Crop the header after x chars. Leave it empty, if you don't want any cropping. I.e: 40|...|1
  templates.listview.header.4.crop  = 40|...|1
  # cat=Browser - Templates - List - Header 4*//104;        type=string;    label= Title:Field with the title property of the a tag. I.e: tx_org_service.teaser_short // tx_org_service.bodytext
  templates.listview.header.4.title  =
  # cat=Browser - Templates - List - Header 4*//105;        type=string;    label= Crop title:Crop the title after x chars. Leave it empty, if you don't want any cropping. I.e: 80|...|1
  templates.listview.header.4.title.crop  = 80|...|1
  # cat=Browser - Templates - List - Header 4*//others/999; type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.listview.header.4.links = Click me!

}