  // 140703: empty statement: for proper comments only
plugin.tx_browser_pi1 {
}

plugin.tx_browser_pi1 {

    // file, css, extensions, add_parameter
  template =
    // myData, system: Will added to cObjData while runtime
  cObjData =
  cObjData {
  }
    // #i0002: Value of given field will added to field. Example: filelink_link = tx_org_downloads.documentssize
  cObjDataFieldWrapper =
  cObjDataFieldWrapper {
  }

    // parseFunc [140703: DEPRECATED]
  general_stdWrap =
  general_stdWrap {
    parseFunc < lib.parseFunc_RTE
    parseFunc {
      nonTypoTagStdWrap {
        encapsLines {
          nonWrappedTag >
        }
      }
    }
  }

    // icon, stdWrap
  document_stdWrap =
  document_stdWrap {
    icon = 1
    stdWrap {
      noTrimWrap = || |
    }
  }

    // strftime
  format =
  format {
      // Default output format for timestamps
    strftime    = {$plugin.tx_browser_pi1.strftime}
    strftime {
        // [BOOLEAN] Change the result of the php method strftime() to ISO, if the value is in UTF-8: 1 (TRUE), 0 (FALSE). 1 is recommended
      utf8_encode = 1
    }
  }

    // TypoScript version. Don't touch this value!
  version = 6.0.7

    // downgrade, localisation, performance, php, recursionGuard, security, session_manager, sql
  advanced =
    // relations, autoDiscover, marker, consolidation
  autoconfig =
    // singlePid, seo, display, templateMarker, selectBox_orderBy, caption_stdWrap, imageCount, imageWrapIfAny, imageBoxWrap, image, master_templates
  displayList =
    // seo, display, templateMarker, content_stdWrap, caption_stdWrap, imageCount, imageWrapIfAny, imageBoxWrap, image, firstImageIsPreview, forceFirstImageIsPreview, noItemMessage, master_templates
  displaySingle =
    // page, mimetypes
  download =
    // csv, map
  export =
    // sDEF, javascript, socialmedia, templating, viewList, viewSingle
  flexform =
    // jquery, ajax, general
  javascript =
    // uid, pid
  localTable =
    // my_ ...
  marker =
    // indexBrowser (A-Z), map, modeSelector, pageBrowser, record_browser
  navigation =
    // page
  rss =
    // uploads
  tt_content =
}

<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/template.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/advanced.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/autoconfig.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/displayList/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/displaySingle/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/download.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/export.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/flexform/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/javascript.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/localTable.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/marker.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/navigation/_setup.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/rss.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/tt_content/uploads.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/_CSS_DEFAULT_STYLE.ts">
<INCLUDE_TYPOSCRIPT: source="FILE:EXT:browser/Configuration/TypoScript/includes/setup/plugin/tx_browser_pi1/_LOCAL_LANG.ts">