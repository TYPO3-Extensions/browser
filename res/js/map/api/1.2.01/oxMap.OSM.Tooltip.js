
'use strict';

window.oxMap.OSM.Tooltip = function( data ){
    var layerOn = null,
        layerCat = null,
        mainEvent,
        eventHandler,
        hrefNoFollow,
        size = oxMap.OSM.configuration.size,

        event = oxMap.OSM.configuration.mapMarkerEvent,
        self = this;

    function checkPosition( mousePos, iconPos, length, type, offset ){
        var maxPos  = mousePos + offset + length;
        return (maxPos < size[ type ] 
                ? 
                iconPos + offset
                :
                iconPos - length) + 'px';
    }

    this.show = function( event, element, icon, mouse ){
        var tooltip = document.createElement('div'),
            elWrap  = element.parentNode,
            name    = element.getAttribute('data-name'),
            cat     = element.getAttribute('data-catID'),
            data    = oxMap.data[ cat ][ 'data' ][ name ];

        tooltip.setAttribute('class', 'oxTextLayer');
        layerOn = oxMap.OSM.tt;

        if( layerOn ){
            layerOn.parentNode.removeChild( layerOn );
            oxMap.OSM.tt = layerOn = null;

            layerCat.style.zIndex = layerCat.getAttribute('data-z');

            if( event.type === 'mouseout' || layerCat.id == elWrap.id ){
                return true;
            }
            return true;
        }

        if( data.desc ){

            oxMap.OSM.tt = layerOn = tooltip;
            layerCat = elWrap;

            tooltip.innerHTML = data.desc;
            tooltip.style.visibility = 'hidden';

            elWrap.setAttribute( 'data-z', elWrap.style.zIndex );
            elWrap.style.zIndex = 100000;
            elWrap.appendChild( tooltip );

            tooltip.style.left = checkPosition( mouse.x, icon.x, tooltip.offsetWidth, 'w', icon.offsetX );
            tooltip.style.top = checkPosition( mouse.y, icon.y, tooltip.offsetHeight, 'h', icon.offsetY );
            tooltip.style.visibility = 'visible';
        }
    };

    this.setup = (function(){
        if( !event && oxMap.Error ){
            new oxMap.Error( 'event' );
            event = 'click';
        }

        eventHandler = oxMap.Helper.getEvent( event ).split(/\s/);
        mainEvent = eventHandler[ 0 ];

        if( mainEvent === 'click' ){
            hrefNoFollow = oxMap.OSM.map.div.getElementsByTagName('a');
            for( var a = 0, b = hrefNoFollow.length; a < b; a += 1 ){
                if( hrefNoFollow[ a ].getAttribute('class').match(/oxMap-markerLink/) ){
                    hrefNoFollow[ a ].removeAttribute('href');
                }
            }
        }

        for( var a = 0, b = eventHandler.length; a < b; a += 1 ){
            oxMap.OSM.map.events.register( eventHandler[a], oxMap.OSM.map, function(evt){
                evt = evt || window.event;

                var target = evt.target || evt.srcElement,
                    tooltipWrapper = target.parentNode,
                    tooltipExists = tooltipWrapper.getAttribute('class');

                if( tooltipExists && !tooltipExists.match(/oxMap-tooltip/) ){
                    tooltipWrapper = tooltipWrapper.parentNode;
                }

                if( target.nodeName.toUpperCase() === 'IMG' && target.getAttribute('class').match(/oxMap-tooltip-icon/) ){
                    self.show( evt
                            , tooltipWrapper
                            , { 
                                x       : tooltipWrapper.offsetLeft
                              , y       : tooltipWrapper.offsetTop
                              , offsetX : target.offsetWidth
                              , offsetY : target.offsetHeight
                              }
                            , this.events.getMousePosition(evt)
                            );
                }
                else{
                    return false;
                }
            });
        }

    })();

};