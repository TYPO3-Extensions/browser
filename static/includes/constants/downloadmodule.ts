plugin.tx_browser_pi1 {
  # cat=Browser - Download Module/enable/100;   type=boolean;   label= Debugging: If enabled, the download module will prompt to the DRS (Development Reporting System)
  downloadmodule.drs = 0
  # cat=Browser - Download Module/enable/102;   type=int+;      label= page uid: Id of the download page
  downloadmodule.pageUid =
  # cat=Browser - Download Module/enable/104;   type=string;    label= Uid parameter:Parameter for the uid of the document in the URL
  downloadmodule.urlParam = downloadUid
  # cat=Browser - Download Module/enable/105;   type=string;    label= Uid of the download single view:Take it from your TypoScript. Default in case of the DAM sample is 37165
  downloadmodule.uidSingleView = 37165
  # cat=Browser - Download Module/dims/100;     type=int+;      label= Thumbnail list view - width: Max width for a thumbnail in the list view
  downloadmodule.thumbnailListViewMaxW = 80
  # cat=Browser - Download Module/dims/101;     type=int+;      label= Thumbnail single view - width: Max width for a thumbnail in the single view
  downloadmodule.thumbnailSingleViewMaxW = 240
  # cat=Browser - Download Module//100;          type=string;   label= field files: table.field with the files (lowercase!):Examples: tx_dam.file_name, tx_org_downloads.documents
  downloadmodule.tableField = tx_org_downloads.documents
  # cat=Browser - Download Module//200;          type=string;   label= Marker field uid: TABLE.FIELD with the uid (uppercase!) of your media record:Examples: TX_DAM.UID, TX_ORG_DOWNLOADS.UID
  downloadmodule.uid = TX_ORG_DOWNLOADS.UID
  # cat=Browser - Download Module//201;          type=string;   label= Marker field files: TABLE.FIELD with the files (uppercase!):Examples: TX_DAM.FILE_NAME, TX_ORG_DOWNLOADS.DOCUMENTS
  downloadmodule.files = TX_ORG_DOWNLOADS.DOCUMENTS
  # cat=Browser - Download Module//202;          type=string;   label= Marker field path: TABLE.FIELD with the files (uppercase!):Examples: TX_DAM.FILE_PATH, TX_ORG_DOWNLOADS.DOCUMENTS_FROM_PATH
  downloadmodule.path = TX_ORG_DOWNLOADS.DOCUMENTS_FROM_PATH
  # cat=Browser - Download Module//203;          type=string;   label= Marker field titles: TABLE.FIELD with the titles (uppercase!):Examples: TX_DAM.TITLE, TX_ORG_DOWNLOADS.TITLE
  downloadmodule.titles = TX_ORG_DOWNLOADS.TITLE
  # cat=Browser - Download Module/others/999;     type=user[EXT:browser/lib/class.tx_browser_extmanager.php:tx_browser_extmanager->promptExternalLinks]; label=Helpful links
  downloadmodule.links = Click me!
}