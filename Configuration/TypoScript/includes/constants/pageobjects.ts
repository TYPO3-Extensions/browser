plugin.tx_browser_pi1 {

  # cat=Browser - Page Objects//100;       type=int+;    label= AJAX page object: Type number (typenum) of the AJAX page object. Default: 28562
  typeNum.ajaxPageObj = 28562
  # cat=Browser - Page Objects//200;       type=int+;    label= CSV page object: Type number (typenum) of the CSV page object. Default: 29370
  typeNum.csvPageObj = 29370
  # cat=Browser - Page Objects//201;       type=string;    label= CSV additionalHeaders: additionalHeaders for the CSV page object.
  typeNum.csvPageObj.additionalHeaders = Content-Type: text/csv | Content-Disposition: attachment; filename="export.csv"
  # cat=Browser - Page Objects//300;       type=int+;    label= Download page object: Type number (typenum) of the page object for downloads. Default: 31230
  typeNum.downloadPageObj = 31230
  # cat=Browser - Page Objects//400;       type=int+;    label= MAP page object: Type number (typenum) of the map page object for map category markers. Default: 32654
  typeNum.mapPageObj = 32654
  # cat=Browser - Page Objects//401;       type=string;    label= MAP additionalHeaders: additionalHeaders for the vCard page object.
  typeNum.mapPageObj.additionalHeaders = Content-Type: text/plain
  # cat=Browser - Page Objects//500;       type=int+;    label= vCard page object: Type number (typenum) of the vCard page object. Default: 67208
  typeNum.vCardPageObj = 67208
  # cat=Browser - Page Objects//501;       type=string;    label= vCard additionalHeaders: additionalHeaders for the vCard page object.
  typeNum.vCardPageObj.additionalHeaders = Content-Type: text/vcard | Content-Disposition: attachment; filename="vcard.vcf"
  # cat=Browser - Page Objects/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  typeNum.pageobjects.links = Click me!

}
