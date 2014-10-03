plugin.tx_browser_pi1 {
    // frameworks
  template {
  }
  template =
  template {
      // Foundation
    frameworks =
    frameworks {
        // components
      foundation =
      foundation {
        components =
        components {
            // topbar
          navigation =
          navigation {
              // default
            topbar =
            topbar {
                // 1, 2, 3, 4, stdWrap, wrap
              default = HMENU
              default {
                1 = TMENU
                1 {
                  expAll    = 1
                  collapse  = 0
                  noBlur    = 1
                  stdWrap {
                    dataWrap (
                      <ul id="dropdown-{field:uid}" class="title-area">
                        <li class="name">
                          <h1><a href="#">{$plugin.tx_browser_pi1.frameworks.foundation.templating.components.navigation.topbar.name}</a></h1>
                        </li>
                        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
                        <li class="toggle-topbar menu-icon">
                          <a href="#"><span>{$plugin.tx_browser_pi1.frameworks.foundation.templating.components.navigation.topbar.icon.label}</span></a>
                        </li>
                      </ul>
                      <section class="top-bar-section">
                        <ul class="{$plugin.tx_browser_pi1.frameworks.foundation.templating.components.navigation.topbar.section.position}">|</ul>
                      </section>
)
                  }
                  NO = 1
                  NO {
                    ATagTitle {
                      field = abstract // subtitle // title
                    }
                    allWrap = <li class="divider divider-first"></li><li class="page-{elementUid} first">|</li><li class="divider"></li> |*| <li class="page-{elementUid}">|</li><li class="divider"></li> |*| <li class="page-{elementUid} last">|</li><li class="divider divider-last"></li>
                    subst_elementUid = 1
                    ATagParams {
                      wrap = class="first level-0" |*| class="all level-0" |*| class="last level-0"
                    }
                    stdWrap {
                      htmlSpecialChars = 1
                    }
                  }
                  ACT < .NO
                  ACT {
                    allWrap = <li class="divider divider-first"></li><li class="active act page-{elementUid} first">|</li><li class="divider"></li> |*| <li class="active act page-{elementUid}">|</li><li class="divider"></li> |*| <li class="active act page-{elementUid} last">|</li><li class="divider divider-last"></li>
                  }
                  ACTIFSUB < .NO
                  ACTIFSUB {
                    allWrap >
                    before {
                      cObject = TEXT
                      cObject {
                        dataWrap = <li class="divider divider-first"></li><li class="active act page-{field:uid} first has-dropdown">| |*| <li class="active act page-{field:uid} has-dropdown">| |*| <li class="active act page-{field:uid} last has-dropdown">|
                      }
                    }
                    wrapItemAndSub = |</li><li class="divider"></li> |*| |</li><li class="divider"></li> |*| |</li><li class="divider divider-last"></li>
                  }
                  CUR < .NO
                  CUR {
                    allWrap = <li class="divider divider-first"></li><li class="active cur page-{elementUid} first">|</li><li class="divider"></li> |*| <li class="active cur page-{elementUid}">|</li><li class="divider"></li> |*| <li class="active cur page-{elementUid} last">|</li><li class="divider divider-last"></li>
                  }
                  CURIFSUB < .NO
                  CURIFSUB {
                    allWrap >
                    before {
                      cObject = TEXT
                      cObject {
                        dataWrap = <li class="divider divider-first"></li><li class="active cur page-{field:uid} first has-dropdown">| |*| <li class="active cur page-{field:uid} has-dropdown">| |*| <li class="active cur page-{field:uid} last has-dropdown">|
                      }
                    }
                    wrapItemAndSub = |</li><li class="divider"></li> |*| |</li><li class="divider"></li> |*| |</li><li class="divider divider-last"></li>
                  }
                  IFSUB < .NO
                  IFSUB {
                    allWrap >
                    before {
                      cObject = TEXT
                      cObject {
                        dataWrap = <li class="divider divider-first"></li><li class="page-{field:uid} first has-dropdown">| |*| <li class="page-{field:uid} has-dropdown">| |*| <li class="page-{field:uid} last has-dropdown">|
                      }
                    }
                    wrapItemAndSub = |</li><li class="divider"></li> |*| |</li><li class="divider"></li> |*| |</li><li class="divider divider-last"></li>
                  }
                }
                2 < .1
                2 {
                  stdWrap {
                    dataWrap = <ul id="dropdown-{field:uid}" class="dropdown dropdown-{register:count_menuItems} page-{field:uid}">|</ul>
                  }
                  NO {
                    ATagParams {
                      wrap = class="level-1"
                    }
                    allWrap = <li class="page-{elementUid} first">|</li> |*| <li class="page-{elementUid}">|</li> |*| <li class="page-{elementUid} last">|</li>
                  }
                  ACT {
                    ATagParams {
                      wrap = class="level-1"
                    }
                    allWrap = <li class="active act page-{elementUid} first">|</li> |*| <li class="active act page-{elementUid}">|</li> |*| <li class="active act page-{elementUid} last">|</li>
                  }
                  ACTIFSUB {
                    ATagParams {
                      wrap = class="level-1"
                    }
                    before {
                      cObject = TEXT
                      cObject {
                        dataWrap = <li class="active act page-{field:uid} first has-dropdown">| |*| <li class="active act page-{field:uid} has-dropdown">| |*| <li class="active act page-{field:uid} last has-dropdown">|
                      }
                    }
                    wrapItemAndSub = |</li>
                  }
                  CUR {
                    ATagParams {
                      wrap = class="level-1"
                    }
                    allWrap = <li class="active cur page-{elementUid} first">|</li> |*| <li class="active cur page-{elementUid}">|</li> |*| <li class="active cur page-{elementUid} last">|</li>
                  }
                  CURIFSUB {
                    ATagParams {
                      wrap = class="level-1"
                    }
                    before {
                      cObject = TEXT
                      cObject {
                        dataWrap = <li class="active cur page-{field:uid} first has-dropdown">| |*| <li class="active cur page-{field:uid} has-dropdown">| |*| <li class="active cur page-{field:uid} last has-dropdown">|
                      }
                    }
                    wrapItemAndSub = |</li>
                  }
                  IFSUB {
                    ATagParams {
                      wrap = class="level-1"
                    }
                    before {
                      cObject = TEXT
                      cObject {
                        dataWrap = <li class="page-{field:uid} first has-dropdown">| |*| <li class="page-{field:uid} has-dropdown">| |*| <li class="page-{field:uid} last has-dropdown">|
                      }
                    }
                    wrapItemAndSub = |</li>
                  }
                }
                3 < .2
                3 {
                  NO {
                    ATagParams {
                      wrap = class="level-2"
                    }
                  }
                  ACT {
                    ATagParams {
                      wrap = class="level-2"
                    }
                  }
                  ACTIFSUB {
                    ATagParams {
                      wrap = class="level-2"
                    }
                  }
                  CUR {
                    ATagParams {
                      wrap = class="level-2"
                    }
                  }
                  CURIFSUB {
                    ATagParams {
                      wrap = class="level-2"
                    }
                  }
                  IFSUB {
                    ATagParams {
                      wrap = class="level-2"
                    }
                  }
                }
                4 < .2
                4 {
                  NO {
                    ATagParams {
                      wrap = class="level-3"
                    }
                  }
                  ACT {
                    ATagParams {
                      wrap = class="level-3"
                    }
                  }
                  ACTIFSUB {
                    ATagParams {
                      wrap = class="level-3"
                    }
                  }
                  CUR {
                    ATagParams {
                      wrap = class="level-3"
                    }
                  }
                  CURIFSUB {
                    ATagParams {
                      wrap = class="level-3"
                    }
                  }
                  IFSUB {
                    ATagParams {
                      wrap = class="level-3"
                    }
                  }
                }
                stdWrap {
                  if {
                    isTrue = {$plugin.tx_browser_pi1.frameworks.foundation.templating.components.navigation.topbar.position}
                  }
                  wrap = <div class="{$plugin.tx_browser_pi1.frameworks.foundation.templating.components.navigation.topbar.position}">|</div>
                }
                wrap = <nav class="top-bar" data-topbar>|</nav>
              }
            }
          }
        }
      }
    }
  }
}

  // Foundation navigation top bar (browser)
tt_content.menu.20.browserFoundationTopNav < plugin.tx_browser_pi1.template.frameworks.foundation.components.navigation.topbar.default