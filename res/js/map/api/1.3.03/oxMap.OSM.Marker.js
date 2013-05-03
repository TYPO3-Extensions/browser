
'use strict';

window.Marker = function( data ){

    var data = oxMap.data,
        marker, m, n, icon, box,
        category = null,
        categoryData = null,
        markerWithSpecialUrl = [],

        self = oxMap.OSM.Marker;

    self.setMarkerByCategory = function( data ){

        for ( var category in data ) {
            categoryData = data[ category ];

            if ( !categoryData.data ){
                continue;
            }

            marker = new OpenLayers.Layer.Markers( category );

            if ( categoryData.icon ) {
                icon = oxMap.OSM.Util.createIcon( categoryData.icon );
            }
            
            for( var key in categoryData.data ){
                var kVal = categoryData.data[ key ],
                    img = icon.clone();

                n = oxMap.OSM.LonLat( kVal.coors );
                m = new OpenLayers.Marker( n, img );
                m.name = key;
                m.category = category;

                if( kVal.url ){
                    m.goToUrl = kVal.url;
                    markerWithSpecialUrl.push( m );
                }
                
                img.imageDiv.getElementsByTagName('img')[0].setAttribute('class','oxMap-tooltip-icon')
                img.imageDiv.setAttribute( 'class', 'oxMap-tooltip' );
                img.imageDiv.setAttribute( 'data-catID', category );
                img.imageDiv.setAttribute( 'data-name', key );

                marker.addMarker( m );
            }

            oxMap.OSM.markerList.push( marker );
        }

        oxMap.OSM.map.addLayers( oxMap.OSM.markerList );

        if( markerWithSpecialUrl.length > 0 ){
            self.setSpecialUrl( markerWithSpecialUrl );
        }

    };

    self.setSpecialUrl = function( marker ){
        var markerLink = document.createElement('a'),
            m, n, mL;

            markerLink.setAttribute( 'class', 'oxMap-markerLink' );

            function setUrl( mark ){
                var markerIcon = mark.icon.imageDiv.getElementsByTagName('img')[0];

                mark.icon.imageDiv.innerHTMl = '';

                mL = markerLink.cloneNode(true);
                mL.setAttribute('href', mark.goToUrl);
                mL.appendChild( markerIcon );
                mark.icon.imageDiv.appendChild( mL ); 
            }

        if( marker.length ){
            for( n = 0, m = marker.length; n < m; n += 1 ){
                setUrl( marker[ n ] );
            }
        }
        else{
            setUrl( marker );
        }

        return true;
    };

    self.setup = (function(){

        oxMap.OSM.markerList = [];

        OpenLayers.Marker.prototype.name = '';
        OpenLayers.Marker.prototype.category = '';
        OpenLayers.Marker.prototype.goToUrl = null;

        self.setMarkerByCategory( data );
    })();

};