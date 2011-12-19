<?php
header('Content-type: plain/txt'); 
echo "
lat	lon	title	description	icon	iconSize	iconOffset
48.9459301	9.6075669	Title One	Description one<br>Second line.<br><br>(click again to close)	typo3conf/ext/browser/res/js/map/test/img/test2.png	14,14	0,-24
48.9899851	9.5382032	Title Two	Description two	typo3conf/ext/browser/res/js/map/test/img/test2.png	14,14	-8,-8

";
?>