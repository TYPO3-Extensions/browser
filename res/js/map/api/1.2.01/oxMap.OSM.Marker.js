
'use strict';

window.oxMap.OSM.Marker = function( data ){

    var data = oxMap.data,
        marker, m, n, icon, box,
        category = null,
        categoryData = null,
        markerWithSpecialUrl = [],

        self = this;

    this.setMarkerByCategory = function( data ){

        for ( category in data ) {
            categoryData = data[ category ];

            if ( !categoryData.data ){
                continue;
            }

            marker = new OpenLayers.Layer.Markers( category );

            if ( categoryData.icon ) {
                icon = oxMap.Helper.OL.createIcon( categoryData.icon );
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

    this.setSpecialUrl = function( marker ){
        var $markerLink = $('<a></a>').addClass( 'oxMap-markerLink' ),
            m, n, $mL,

            setUrl = function( mark ){
            
                var $markerIcon = $( mark.icon.imageDiv ).find('img'),
                    $mL = $markerLink.clone();
                    $markerIcon.wrap( $mL.attr( 'href', mark.goToUrl ) );
            };

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

    this.setup = (function(){

        oxMap.OSM.markerList = [];

        OpenLayers.Marker.prototype.name = '';
        OpenLayers.Marker.prototype.category = '';
        OpenLayers.Marker.prototype.goToUrl = null;

        self.setMarkerByCategory( data );

    })();

};