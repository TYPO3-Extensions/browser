plugin.tx_browser_pi1 {
  download {
    page = PAGE
    page {
      typeNum = {$plugin.tx_browser_pi1.typeNum.downloadPageObj}
      config {
        disableAllHeaderCode  = 1
        disablePrefixComment  = 1
        metaCharset           = iso-8859-15
        xhtml_cleaning        = 0
        admPanel              = 0
//        additionalHeaders     = Content-Type: text/csv | Content-Disposition: attachment; filename="export.csv"
      }
      10 = CONTENT
      10 {
        table=tt_content
        select{
            // use current language
          languageField = sys_language_uid
          andWhere {
            cObject = COA
            cObject {
                // choose the current Browser plugin
              10 = TEXT
              10 {
                // #i0170, 150430, dwildt, 1-
                //data = GP:tx_browser_pi1|plugin
                // #i0170, 150430, dwildt, 4+
                stdWrap {
                  data    = GP:tx_browser_pi1|plugin
                  intval  = 1
                }
                if {
                  isTrue {
                    data = GP:tx_browser_pi1|plugin
                  }
                }
                wrap = uid=|
              }
            }
          }
        }
      }
    }
    mimetypes {
      see = http://de.selfhtml.org/diverses/mimetypen.htm
      fileext {
        ai    = application/postscript
        aif   = audio/x-aiff
        aifc  = audio/x-aiff
        aiff  = audio/x-aiff
        avi   = video/x-msvideo
        bin   = application/octet-stream
        bin   = application/x-macbinary
        cab   = application/x-shockwave-flash
        class = application/octet-stream
        com   = application/octet-stream
        css   = text/css
        csv   = text/comma-separated-values
        dcr   = application/x-director
        dir   = application/x-director
        dll   = application/octet-stream
        doc   = application/msword
        dot   = application/msword
        dvi   = application/x-dvi
        dxr   = application/x-director
        eps   = application/postscript
        etx   = text/x-setext
        exe   = application/octet-stream
        fh4   = image/x-freehand
        fh5   = image/x-freehand
        fhc   = image/x-freehand
        gif   = image/gif
        gtar  = application/x-gtar
        gz    = application/gzip
        hqx   = application/mac-binhex40
        htm   = application/xhtml+xml
        html  = application/xhtml+xml
        ico   = image/x-icon
        jpe   = image/jpeg
        jpeg  = image/jpeg
        jpg   = image/jpeg
        js    = text/javascript
        latex = application/x-latex
        mid   = audio/x-midi
        midi  = audio/x-midi
        mov   = video/quicktime
        movie = video/x-sgi-movie
        mp2   = audio/x-mpeg
        mpe   = video/mpeg
        mpeg  = video/mpeg
        mpg   = video/mpeg
        pbm   = image/x-portable-bitmap
        pdf   = application/pdf
        pgm   = image/x-portable-graymap
        php   = application/x-httpd-php
        phtml = application/x-httpd-php
        png   = image/png
        pot   = application/mspowerpoint
        ppm   = image/x-portable-pixmap
        pps   = application/mspowerpoint
        ppt   = application/mspowerpoint
        ppz   = application/mspowerpoint
        ps    = application/postscript
        qt    = video/quicktime
        ra    = audio/x-pn-realaudio
        ram   = audio/x-pn-realaudio
        rgb   = image/x-rgb
        rpm   = audio/x-pn-realaudio-plugin
        rtc   = application/rtc
        rtf   = text/rtf
        rtx   = text/richtext
        sgm   = text/x-sgml
        sgml  = text/x-sgml
        sh    = application/x-sh
        shar  = application/x-shar
        shtml = application/xhtml+xml
        spc   = text/x-speech
        stream = audio/x-qt-stream
        swf   = application/x-shockwave-flash
        talk  = text/x-speech
        tar   = application/x-tar
        tex   = application/x-tex
        texi  = application/x-texinfo
        texinfo = application/x-texinfo
        tif   = image/tiff
        tiff  = image/tiff
        txt   = text/plain
        viv   = video/vnd.vivo
        vivo  = video/vnd.vivo
        wav   = audio/x-wav
        wbmp  = image/vnd.wap.wbmp
        wml   = text/vnd.wap.wml
        wmlc  = application/vnd.wap.wmlc
        wmls  = text/vnd.wap.wmlscript
        wmlsc = application/vnd.wap.wmlscriptc
        xhtml = application/xhtml+xml
        xla   = application/msexcel
        xls   = application/msexcel
        xml   = application/xml
        xml   = text/xml
        z     = application/x-compress
        zip   = application/zip
      }
    }
  }
}