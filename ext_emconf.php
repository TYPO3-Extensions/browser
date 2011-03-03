<?php

########################################################################
# Extension Manager/Repository config file for ext "browser".
#
# Auto generated 03-02-2011 15:38
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Browser - TYPO3 Frontend Engine: 44541',
	'description' => 'Browser - the TYPO3 Frontend Engine - displays content from related tables. You need 1 line typoscript for a result list with a search form, a record browser and an a-z browser. Images are wrapped self-acting. SEO, Search Engine Optimisation. DRS supports the TypoScript configuration. With manual and tutorial.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '3.6.2',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Dirk Wildt (Die Netzmacher)',
	'author_email' => 'http://wildt.at.die-netzmacher.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.1.0-0.0.0',
			'typo3' => '4.4.6-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:157:{s:9:"ChangeLog";s:4:"0ee4";s:21:"ext_conf_template.txt";s:4:"4a64";s:12:"ext_icon.gif";s:4:"2bd8";s:17:"ext_localconf.php";s:4:"6504";s:15:"ext_php_api.dat";s:4:"ff37";s:14:"ext_tables.php";s:4:"5a80";s:16:"locallang_db.xml";s:4:"1fdb";s:14:"doc/manual.pdf";s:4:"a79b";s:14:"doc/manual.sxw";s:4:"279a";s:35:"lib/class.tx_browser_extmanager.php";s:4:"8e8c";s:17:"lib/locallang.xml";s:4:"251c";s:37:"lib/icons/die-netzmacher.de_200px.gif";s:4:"48b3";s:31:"lib/icons/your-logo_de-blue.gif";s:4:"19f7";s:31:"lib/icons/your-logo_de-grey.gif";s:4:"1fbc";s:36:"lib/icons/your-logo_default-blue.gif";s:4:"710c";s:36:"lib/icons/your-logo_default-grey.gif";s:4:"6fdc";s:22:"pi1/browser_wizard.gif";s:4:"ba83";s:28:"pi1/class.tx_browser_pi1.php";s:4:"2f7e";s:36:"pi1/class.tx_browser_pi1_backend.php";s:4:"4f2c";s:44:"pi1/class.tx_browser_pi1_backend_wizicon.php";s:4:"1856";s:32:"pi1/class.tx_browser_pi1_cal.php";s:4:"ddc7";s:35:"pi1/class.tx_browser_pi1_config.php";s:4:"d9ce";s:40:"pi1/class.tx_browser_pi1_consolidate.php";s:4:"f1ef";s:35:"pi1/class.tx_browser_pi1_filter.php";s:4:"a3dc";s:39:"pi1/class.tx_browser_pi1_javascript.php";s:4:"61cc";s:41:"pi1/class.tx_browser_pi1_localization.php";s:4:"c999";s:35:"pi1/class.tx_browser_pi1_marker.php";s:4:"3571";s:38:"pi1/class.tx_browser_pi1_multisort.php";s:4:"924a";s:33:"pi1/class.tx_browser_pi1_navi.php";s:4:"b28b";s:32:"pi1/class.tx_browser_pi1_seo.php";s:4:"fabe";s:40:"pi1/class.tx_browser_pi1_socialmedia.php";s:4:"0220";s:37:"pi1/class.tx_browser_pi1_sql_auto.php";s:4:"747c";s:42:"pi1/class.tx_browser_pi1_sql_functions.php";s:4:"8913";s:39:"pi1/class.tx_browser_pi1_sql_manual.php";s:4:"39b5";s:32:"pi1/class.tx_browser_pi1_tca.php";s:4:"6fda";s:37:"pi1/class.tx_browser_pi1_template.php";s:4:"cfe7";s:40:"pi1/class.tx_browser_pi1_ttcontainer.php";s:4:"67dd";s:39:"pi1/class.tx_browser_pi1_typoscript.php";s:4:"d58f";s:34:"pi1/class.tx_browser_pi1_views.php";s:4:"5578";s:36:"pi1/class.tx_browser_pi1_wrapper.php";s:4:"e8e0";s:31:"pi1/class.tx_browser_pi1_zz.php";s:4:"6c42";s:16:"pi1/flexform.xml";s:4:"ae7d";s:26:"pi1/flexform_locallang.php";s:4:"34a5";s:31:"pi1/flexform_sheet_advanced.xml";s:4:"5927";s:32:"pi1/flexform_sheet_checklist.xml";s:4:"02ec";s:34:"pi1/flexform_sheet_development.xml";s:4:"ad2e";s:33:"pi1/flexform_sheet_javascript.xml";s:4:"141b";s:27:"pi1/flexform_sheet_sDEF.xml";s:4:"c70f";s:29:"pi1/flexform_sheet_search.xml";s:4:"d844";s:34:"pi1/flexform_sheet_socialmedia.xml";s:4:"6a40";s:26:"pi1/flexform_sheet_tca.xml";s:4:"cb65";s:33:"pi1/flexform_sheet_templating.xml";s:4:"a546";s:31:"pi1/flexform_sheet_viewList.xml";s:4:"0ff6";s:17:"pi1/locallang.xml";s:4:"a385";s:28:"pi2/class.tx_browser_pi2.php";s:4:"8373";s:36:"pi2/class.tx_browser_pi2_checker.php";s:4:"1248";s:36:"pi2/class.tx_browser_pi2_tickets.php";s:4:"130c";s:17:"pi2/locallang.xml";s:4:"36da";s:22:"pi3/browser_wizard.gif";s:4:"ba83";s:28:"pi3/class.tx_browser_pi3.php";s:4:"4e4a";s:44:"pi3/class.tx_browser_pi3_backend_wizicon.php";s:4:"353b";s:16:"pi3/flexform.xml";s:4:"9270";s:32:"pi3/flexform_sheet_checklist.xml";s:4:"b476";s:20:"pi3/flexform_tca.php";s:4:"f582";s:17:"pi3/locallang.xml";s:4:"5650";s:21:"pi3/icons/browser.gif";s:4:"2bd8";s:24:"pi3/icons/green_cars.gif";s:4:"6704";s:24:"pi3/icons/job_market.gif";s:4:"7159";s:23:"pi3/icons/organiser.gif";s:4:"ec42";s:24:"pi3/icons/quick_shop.gif";s:4:"2501";s:24:"pi3/static/constants.txt";s:4:"d41d";s:20:"pi3/static/setup.txt";s:4:"0638";s:22:"pi4/browser_wizard.gif";s:4:"ba83";s:28:"pi4/class.tx_browser_pi4.php";s:4:"56be";s:44:"pi4/class.tx_browser_pi4_backend_wizicon.php";s:4:"48b6";s:24:"pi4/static/constants.txt";s:4:"d41d";s:20:"pi4/static/setup.txt";s:4:"0d65";s:22:"pi5/browser_wizard.gif";s:4:"ba83";s:36:"pi5/class.tx_browser_pi5_backend.php";s:4:"1965";s:44:"pi5/class.tx_browser_pi5_backend_wizicon.php";s:4:"3eb2";s:16:"pi5/flexform.xml";s:4:"acef";s:26:"pi5/flexform_locallang.php";s:4:"1f7e";s:26:"pi5/flexform_sheet_day.xml";s:4:"c70f";s:28:"pi5/flexform_sheet_month.xml";s:4:"c70f";s:27:"pi5/flexform_sheet_sDEF.xml";s:4:"139b";s:27:"pi5/flexform_sheet_week.xml";s:4:"c70f";s:27:"pi5/flexform_sheet_year.xml";s:4:"fbb8";s:20:"pi5/res/default.tmpl";s:4:"cf48";s:24:"pi5/static/constants.txt";s:4:"d41d";s:20:"pi5/static/setup.txt";s:4:"2e00";s:19:"res/a-z_Browser.txt";s:4:"c5e6";s:16:"res/default.tmpl";s:4:"0096";s:19:"res/default_ul.tmpl";s:4:"e2d6";s:15:"res/favicon.ico";s:4:"2ef3";s:38:"res/images/alternate_image_400x300.gif";s:4:"382e";s:29:"res/images/browser_loader.gif";s:4:"b72b";s:31:"res/images/browser_rss-feed.gif";s:4:"a1fd";s:44:"res/images/socialmedia/bookmarks/addthis.png";s:4:"77cf";s:40:"res/images/socialmedia/bookmarks/ask.png";s:4:"3c28";s:45:"res/images/socialmedia/bookmarks/backflip.png";s:4:"3570";s:46:"res/images/socialmedia/bookmarks/blinkbits.png";s:4:"544a";s:46:"res/images/socialmedia/bookmarks/blinklist.png";s:4:"3c9b";s:46:"res/images/socialmedia/bookmarks/blogmarks.png";s:4:"4593";s:44:"res/images/socialmedia/bookmarks/bluedot.png";s:4:"e71c";s:45:"res/images/socialmedia/bookmarks/connotea.png";s:4:"d19c";s:46:"res/images/socialmedia/bookmarks/delicious.png";s:4:"07a9";s:46:"res/images/socialmedia/bookmarks/delirious.png";s:4:"d5f3";s:41:"res/images/socialmedia/bookmarks/digg.png";s:4:"5d3f";s:45:"res/images/socialmedia/bookmarks/facebook.png";s:4:"4881";s:41:"res/images/socialmedia/bookmarks/fark.png";s:4:"fd3d";s:48:"res/images/socialmedia/bookmarks/feedmelinks.png";s:4:"2eab";s:42:"res/images/socialmedia/bookmarks/folkd.png";s:4:"0a1a";s:41:"res/images/socialmedia/bookmarks/furl.png";s:4:"7056";s:43:"res/images/socialmedia/bookmarks/google.png";s:4:"523c";s:41:"res/images/socialmedia/bookmarks/hype.png";s:4:"9236";s:46:"res/images/socialmedia/bookmarks/linkagogo.png";s:4:"d7c8";s:46:"res/images/socialmedia/bookmarks/linkarena.png";s:4:"596e";s:41:"res/images/socialmedia/bookmarks/live.png";s:4:"90e7";s:45:"res/images/socialmedia/bookmarks/magnolia.png";s:4:"eda8";s:45:"res/images/socialmedia/bookmarks/mylinkde.png";s:4:"de0d";s:45:"res/images/socialmedia/bookmarks/netscape.png";s:4:"f977";s:44:"res/images/socialmedia/bookmarks/netvouz.png";s:4:"84bb";s:45:"res/images/socialmedia/bookmarks/newsvine.png";s:4:"f2a8";s:44:"res/images/socialmedia/bookmarks/oneview.png";s:4:"d013";s:45:"res/images/socialmedia/bookmarks/rawsugar.png";s:4:"47bb";s:43:"res/images/socialmedia/bookmarks/reddit.png";s:4:"fddf";s:44:"res/images/socialmedia/bookmarks/scuttle.png";s:4:"000a";s:42:"res/images/socialmedia/bookmarks/simpy.png";s:4:"e389";s:45:"res/images/socialmedia/bookmarks/smarking.png";s:4:"7614";s:42:"res/images/socialmedia/bookmarks/spurl.png";s:4:"d5db";s:39:"res/images/socialmedia/bookmarks/su.png";s:4:"e3d0";s:44:"res/images/socialmedia/bookmarks/tagthat.png";s:4:"00ab";s:45:"res/images/socialmedia/bookmarks/tailrank.png";s:4:"0084";s:47:"res/images/socialmedia/bookmarks/technorati.png";s:4:"c943";s:44:"res/images/socialmedia/bookmarks/twitter.png";s:4:"a10b";s:44:"res/images/socialmedia/bookmarks/webnews.png";s:4:"1e31";s:41:"res/images/socialmedia/bookmarks/wink.png";s:4:"d753";s:42:"res/images/socialmedia/bookmarks/wists.png";s:4:"db99";s:41:"res/images/socialmedia/bookmarks/wong.png";s:4:"9ff2";s:47:"res/images/socialmedia/bookmarks/yahoomyweb.png";s:4:"4bad";s:43:"res/images/socialmedia/bookmarks/yiggit.png";s:4:"fd6f";s:24:"res/js/tx_browser_pi1.js";s:4:"1b90";s:29:"res/js/tx_browser_pi1_ajax.js";s:4:"4ec5";s:39:"res/js/tx_browser_pi1_ajax_languages.js";s:4:"ee12";s:31:"res/sample/tt_news/default.tmpl";s:4:"43a0";s:27:"res/sample/tt_news/rss.tmpl";s:4:"7c99";s:19:"res/ts/ajaxPage.txt";s:4:"3aa5";s:27:"res/ts/rssAlternateLink.txt";s:4:"22a8";s:18:"res/ts/rssPage.txt";s:4:"4249";s:27:"res/tutorial_01/step_02.txt";s:4:"8d0c";s:27:"res/tutorial_01/step_03.txt";s:4:"7d1e";s:27:"res/tutorial_01/step_04.txt";s:4:"658b";s:27:"res/tutorial_01/step_05.txt";s:4:"2217";s:20:"static/constants.txt";s:4:"2f90";s:16:"static/setup.txt";s:4:"2957";s:36:"static/samples/tt_news/constants.txt";s:4:"0739";s:32:"static/samples/tt_news/setup.txt";s:4:"8d04";}',
	'suggests' => array(
	),
);

?>