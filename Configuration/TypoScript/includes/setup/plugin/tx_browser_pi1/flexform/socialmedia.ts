plugin.tx_browser_pi1 {
  flexform {
    socialmedia {
      socialbookmarks {
        wraps {
          stdWrap_items {
            value = ###BOOKMARK_ITEMS###
            wrap  = <span class="tx_browser_pi1.bookmark_items">|</span>
          }
          stdWrap_item {
            value = <a href="###BOOKMARK_URL###" target="_blank" title="###BOOKMARK_NAME###"><img ###BOOKMARK_IMAGESIZE### src="###BOOKMARK_IMAGE###"></a>
            wrap  = <span class="tx_browser_pi1.bookmark_item">|</span>
          }
        }
        bookmarks {
          items {
            addthis {
              name    = Add This
              url     = http://www.addthis.com/bookmark.php?pub=geotek&amp;url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/addthis.png
              stdWrap {
                value = <a title="###BOOKMARK_NAME###" href="###BOOKMARK_URL###"><img ###BOOKMARK_IMAGESIZE### src="###BOOKMARK_IMAGE###"></a>
                wrap  = <span class="tx_browser_pi1.bookmark_item tx_browser_pi1.bookmark_item_first">|</span>
              }
            }
            ask {
              name    = Ask.com
              url     = http://myjeeves.ask.com/mysearch/BookmarkIt?v=1.2&amp;t=webpages&amp;url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/ask.png
            }
            backflip {
              name    = Backflip
              url     = http://www.backflip.com/add_page_pop.ihtml?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/backflip.png
            }
            blinkbits {
              name    = BlinkBits
              url     = http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;source_url=###URL###&amp;title=###TITLE###&amp;body=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/blinkbits.png
            }
            blinklist {
              name    = blinklist
              url     = http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Description=&amp;Url=###URL###&amp;Title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/blinklist.png
            }
            blogmarks {
              name    = BlogMarks
              url     = http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/blogmarks.png
            }
            bluedot {
              name    = Bluedot
              url     = http://bluedot.us/Authoring.aspx?u=###URL###&amp;t=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/bluedot.png
            }
            connotea {
              name    = Connotea
              url     = http://www.connotea.org/addpopup?continue=confirm&amp;uri=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/connotea.png
            }
            delicious {
              name    = del.icio.us
              url     = http://del.icio.us/post?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/delicious.png
            }
            delirious {
              name    = de.lirio.us
              url     = http://de.lirio.us/rubric/post?uri=###URL###;title=###TITLE###;when_done=go_back
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/delirious.png
            }
            digg {
              name    = digg.com
              url     = http://digg.com/submit?phase=2&amp;url=###URL###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/digg.png
            }
            facebook {
              name    = Facebook
              url     = http://www.facebook.com/share.php?u=###URL###&t=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/facebook.png
            }
            fark {
              name    = Fark.com
              url     = http://cgi.fark.com/cgi/fark/edit.pl?new_url=###URL###&amp;new_comment=###TITLE###&amp;linktype=Misc
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/fark.png
            }
            feedmelinks {
              name    = Feed me links
              url     = http://feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;url=###URL###name=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/feedmelinks.png
            }
            folkd {
              name    = Folkd
              url     = http://www.folkd.com/submit/###URL###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/folkd.png
            }
            furl {
              name    = Furl
              url     = http://www.furl.net/storeIt.jsp?u=###URL###&amp;t=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/furl.png
            }
            google {
              name    = google.com
              url     = http://www.google.com/bookmarks/mark?op=add&amp;bkmk=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/google.png
            }
            hype {
              name    = hype it!
              url     = http://hype.yeebase.com/submit/###URL###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/hype.png
            }
            linkagogo {
              name    = LinkaGoGo
              url     = http://www.linkagogo.com/go/AddNoPopup?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/linkagogo.png
            }
            linkarena {
              name    = LinkaARENA
              url     = http://linkarena.com/bookmarks/addlink/?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/linkarena.png
            }
            magnolia {
              name    = Ma.gnolia
              url     = http://ma.gnolia.com/beta/bookmarklet/add?url=###URL###&amp;title=###TITLE###&amp;description=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/magnolia.png
            }
            misterwong {
              name    = Mister Wong
              url     = http://www.mister-wong.de/index.php?action=addurl&amp;bm_url=###URL###&amp;bm_description=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/wong.png
            }
            mylinkde {
              name    = MyLink.de
              url     = http://www.mylink.de/qa.asp?link=###URL###&amp;bez=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/mylinkde.png
            }
            netscape {
              name    = Netscape
              url     = http://www.netscape.com/submit/?U=###URL###&amp;T=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/netscape.png
            }
            netvouz {
              name    = netvouz
              url     = http://www.netvouz.com/action/submitBookmark?url=###URL###&amp;title=###TITLE###&amp;description=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/netvouz.png
            }
            newsvine {
              name    = newsvine.com
              url     = http://www.newsvine.com/_tools/seed&amp;save?u=###URL###&amp;h=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/newsvine.png
            }
            oneview {
              name    = oneview
              url     = http://www.oneview.de/quickadd/neu/addBookmark.jsf?URL=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/oneview.png
            }
            rawsugar {
              name    = RawSugar
              url     = http://www.rawsugar.com/tagger/?turl=###URL###&amp;tttl=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/rawsugar.png
            }
            reddit {
              name    = Reddit
              url     = http://reddit.com/submit?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/reddit.png
            }
            scuttle {
              name    = Scuttle
              url     = http://www.scuttle.org/bookmarks.php/maxpower?action=add&amp;address=###URL###&amp;title=###TITLE###&amp;description=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/scuttle.png
            }
            simpy {
              name    = Simpy
              url     = http://www.simpy.com/simpy/LinkAdd.do?href=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/simpy.png
            }
            smarking {
              name    = Smarking
              url     = http://smarking.com/editbookmark/?url=###URL###&amp;description=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/smarking.png
            }
            spurl {
              name    = Spurl
              url     = http://www.spurl.net/spurl.php?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/spurl.png
            }
            stumbleupon {
              name    = stumbleupon.com
              url     = http://www.stumbleupon.com/submit?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/su.png
            }
            tagthat {
              name    = TagThat
              url     = http://www.tagthat.de/bookmarken.php?action=neu&amp;url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/tagthat.png
            }
            tailrank {
              name    = TailRank
              url     = http://tailrank.com/share/?text=&amp;link_href=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/tailrank.png
            }
            technorati {
              name    = Technorati
              url     = http://technorati.com/faves/?add=###URL###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/technorati.png
            }
            twitter {
              name    = Twitter
              url     = http://twitter.com/home?status=###TITLE###+-+###URL###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/twitter.png
            }
            webnews {
              name    = Webnews
              url     = http://www.webnews.de/einstellen?url=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/webnews.png
            }
            wink {
              name    = Wink
              url     = http://www.wink.com/_/tag?url=###URL###&amp;doctitle=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/wink.png
            }
            wists {
              name    = Wists
              url     = http://wists.com/r.php?c=&amp;r=###URL###&amp;title=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/wists.png
            }
            yahoomyweb {
              name    = YahooMyWeb
              url     = http://myweb.yahoo.com/myresults/bookmarklet?t=###TITLE###&amp;u=###URL###&amp;ei=UTF-8
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/yahoomyweb.png
            }
            yiggde {
              name    = YiggIt
              url     = http://yigg.de/neu?exturl=###URL###&amp;exttitle=###TITLE###&amp;extdesc=###TITLE###
              image   = EXT:browser/Resources/Public/Images/Socialmedia/bookmarks/yiggit.png
            }
          }
        }
      }
    }
  }
}