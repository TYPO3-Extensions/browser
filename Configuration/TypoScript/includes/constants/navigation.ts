plugin.tx_browser_pi1 {

  # cat=Browser - Navigation//101;            type=string;  label= showUid: Alias for the parameter showUid. WARNING: Set this property for main plugins only, don't set it for plugins in the marginal column. The alias simplifies the configuration of realUrl. I.e: staffUid.
  navigation.showUid = showUid
  # cat=Browser - Navigation//102;            type=boolean; label= Workaround latin1: Workaround for the index browser in case of trouble with UTF8: Send the SQL query 'SET NAMES latin1' before other any query.
  navigation.indexbrowser.workaroundLatin1 =
  # cat=Browser - Navigation/others/999;      type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  navigation.links = Click me!

}