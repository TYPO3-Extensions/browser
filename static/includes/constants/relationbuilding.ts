plugin.tx_browser_pi1 {

  # cat=Browser - Relation Buildung/enable; type=boolean; label= SQL Relation Building Automatically: Try to build relations automatically? TRUE is recommended.
  autoSQLrelConfig = 1
  # cat=Browser - Relation Buildung/enable; type=boolean; label= SQL Relation Building Oneway Only: Use only relations from the local table to the foreign table? TRUE is recommended.
  autoSQLrelOnlyOneway = 1
  # cat=Browser - Relation Buildung/enable; type=boolean; label= SQL Simple Relation with Self References: Use self references in simple relations? FALSE is recommended.
  autoSQLrelSelfReference = 0
  # cat=Browser - Relation Buildung/enable; type=boolean; label= Discover Title: Try to discover fields with titles and wrap them as the header? Only for single views. TRUE is recommended.
  autoDiscoverTitle = 1
  # cat=Browser - Relation Buildung/enable; type=boolean; label= Discover Timestamps: Try to discover fields with timestamps and wrap them in a human readable format (See format in the section 'Others' below) ? TRUE is recommended.
  autoDiscoverTimestamp = 1
  # cat=Browser - Relation Buildung/enable; type=boolean; label= Discover Images: Try to discover fields with image file names, image capture, image alttext and image titletext. Wrap it as an image object? TRUE is recommended.
  autoDiscoverImage = 1
  # cat=Browser - Relation Buildung/enable; type=boolean; label= Discover Documents: Try to discover fields with document file names and wrap values as documents? TRUE is recommended.
  autoDiscoverDocument = 1
  # cat=Browser - Relation Buildung/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  relationBuildung.links = Click me!

}