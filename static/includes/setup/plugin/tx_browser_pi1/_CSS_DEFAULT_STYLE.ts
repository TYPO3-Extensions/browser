plugin.tx_browser_pi1 {

  _CSS_DEFAULT_STYLE (
    .tx-browser-pi1 div.azSelector,
    .tx-browser-pi1 div.indexBrowser,
    .tx-browser-pi1 div.modeSelector,
    .tx-browser-pi1 div.XXXrecordBrowser {
      padding:.4em 0;
      /* 111218, 3.9.6, dwildt-*/
      /*height:1.4em;*/
    }
    .tx-browser-pi1 ul.azSelector,
    .tx-browser-pi1 ul.indexBrowser,
    .tx-browser-pi1 ul.modeSelector,
    .tx-browser-pi1 ul.XXXrecordBrowser {
      max-width: 100%;
      margin: 0;
      padding: 0;
      font-size:1em;
      height:1.4em;
      background:#EEE;
    }
    * html .tx-browser-pi1 ul.azSelector,
    * html .tx-browser-pi1 ul.indexBrowser,
    * html .tx-browser-pi1 ul.modeSelector,
    * html .tx-browser-pi1 ul.XXXrecordBrowser {
      width: 100%;
    }
    .tx-browser-pi1 ul.azSelector li,
    .tx-browser-pi1 ul.indexBrowser li,
    .tx-browser-pi1 ul.modeSelector li,
    .tx-browser-pi1 ul.XXXrecordBrowser li {
      display: block;
      margin: 0;
      padding: 0 .4em;
      float: left;
      border-right:1px solid grey;
    }
    .tx-browser-pi1 ul.azSelector li.last,
    .tx-browser-pi1 ul.indexBrowser li.last,
    .tx-browser-pi1 ul.modeSelector li.last,
    .tx-browser-pi1 ul.XXXrecordBrowser li.last {
      border-right:0;
    }
    .tx-browser-pi1 ul.azSelector li.selected,
    .tx-browser-pi1 ul.indexBrowser li.selected,
    .tx-browser-pi1 ul.modeSelector li.selected,
    .tx-browser-pi1 ul.XXXrecordBrowser li.selected {
      background:darkorange;
    }
    .tx-browser-pi1 .ui-tabs ul li {
      font-size:0.75em;
      font-weight:bold;
    }
    .tx-browser-pi1 .ui-tabs ul li  a {
      padding:0.3em 0.5em 0.1em;
    }
    .tx-browser-pi1 .ui-tabs .without-href {
      opacity: 0.20;
      cursor:default;
    }
    .tx-browser-pi1 .ui-tabs-list {
      padding:0.2em;
    }
    select#tx_browser_pi1_radius {
      padding-right:.2em;
      text-align:right;
    }
    select#tx_browser_pi1_radius option{
      padding-right:1em;
    }
    /* #9659 */
    .returntolist {
      cursor: pointer;
    }
    .txbrowserpi1loader {
      background: #fff url(/typo3conf/ext/browser/res/images/browser_loader.gif) no-repeat  center ;
      background-color: #fff;
      opacity:.8;
      position: relative;
      top:0;
      left:0;
      width:100%;
      height:300px;
      margin-bottom:-300px;
      z-index:2;
    }
    .loading {
      opacity:.8;
    }
    .txbrowserpi1ajaxerror {
      color: #d00;
    }
    /* #9659 */

  )
}