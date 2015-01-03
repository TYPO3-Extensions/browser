plugin.tx_browser_pi1 {

  # cat=Browser - Templates - List - Text 6*//101;          type=string;    label= Text for listview:Field with the text for the list view. I.e: tx_org_service.teaser_short // tx_org_service.short
  templates.listview.text.6.field  =
  # cat=Browser - Templates - List - Text 6*//102;          type=options[div,h1,h2,h3,h4,h5,h6,p];  label= Tag:HTML tag. I.e: p
  templates.listview.text.6.tag   = p
  # cat=Browser - Templates - List - Text 6*//103;          type=string;    label= Crop text:Crop the text after x chars. Leave it empty, if you don't want any cropping. I.e: 300|...|1
  templates.listview.text.6.crop    = 300|...|1
  # cat=Browser - Templates - List - Text 6*//others/999;   type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  templates.listview.text.6.links = Click me!

}
