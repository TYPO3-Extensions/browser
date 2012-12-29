
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

            $.each( categoryData.data, function( key, kVal ){
                var img = icon.clone();

                n = oxMap.OSM.LonLat( kVal.coors );
                m = new OpenLayers.Marker( n, img );
                m.name = key;
                m.category = category;

                if( kVal.url ){
                    m.goToUrl = kVal.url;
                    markerWithSpecialUrl.push( m );
                }

                $(img.imageDiv).addClass( 'tooltip' )
                               .attr( 'data-catID', category )
                               .attr( 'data-name', key );

                marker.addMarker( m );

            });

            oxMap.OSM.markerList.push( marker );
        }

        oxMap.OSM.map.addLayers( oxMap.OSM.markerList );

        if( markerWithSpecialUrl.length > 0 ){
            self.setSpecialUrl( markerWithSpecialUrl );
        }

    };

    this.setSpecialUrl = function( marker ){
        var $markerLink = $('<a></a>').addClass( 'markerLink' ),
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




window.oxMap.OSM.Tooltip = function( data ){
    var $map = $(oxMap.OSM.map.div),
        $layerOn = null,
        $layerCat = null,

        event = oxMap.OSM.configuration.mapMarkerEvent,
        self = this;

    this.show = function( event ){
        event.stopPropagation();
        event.preventDefault();

        var $tooltip = $( '<div class="oxTextLayer"></div>' ),
            $el = $(this),
            $elWrap = $el.parent(),
            name = $el.attr('data-name'),
            cat = $el.attr('data-catID'),
            data = oxMap.data[ cat ][ 'data' ][ name ];

        if( $layerOn ){
            $layerOn.remove();
            $layerOn = null;
            $layerCat.css( 'z-index', $layerCat.attr('data-z') );

            if( event.type === 'mouseout' || $layerCat.attr('id') == $elWrap.attr('id') ){
                return true;
            }
        }

        $layerOn = $tooltip;
        $layerCat = $elWrap;

        if( data.desc ){
            $el.append( $tooltip.html( data.desc ) );
            $elWrap.attr( 'data-z', $elWrap.css('z-index') )
                   .css( 'z-index', 100000 );
        }
    };

    this.setup = (function(){
        if( !event ){
            new oxMap.Error( 'event' );
            event = 'click';
        }

        $map.on( oxMap.Helper.getEvent( event ), '.tooltip', self.show );
    })();

};