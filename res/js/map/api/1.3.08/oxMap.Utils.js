
'use strict';

window.Utils = {

    getEvent : function( eventName ){
        switch( eventName ){                                                                              //  returns different OL event handling objects
        case 'hover':
            return 'mouseover mouseout';
            break;
        case 'click':
            return 'click';
            break;
        default:
            return null;
        }
    }

  , getUsedImageType : function(){
    	return 'png';
        //return ( $.browser.msie && parseInt( $.browser.version ) < 9 ) ? 'png' : 'svg';
    }

  , preLoadLayerImages : function( images ){
    	for( var pic in images ){
    		var img = new Image();
    		img.src = images[ pic ];
    	}
    }

  , merge : function( base, data ){
    	for( var a in data ){
    		for( var b in base ){
    			if( a === b ){
    				base[ b ] = data[ a ];
    			}
    			else{
    				base[ a ] = data[ a ];
    			}
    		}
    	}
    	return base;
    }

  , getElementDimensions : function( element, type ){
	  	var dim;

	  	dim = oxMap.Utils.merge( {
			w : element.offsetWidth
		  , h : element.offsetHeight
		}, oxMap.GMap.Util.getElementPosition( element ) );

	  	if( type ){
	  		return dim[ type ];
	  	}
	  	return dim;
	}

  , delObject : function( name, lit ){
		try{
			if( lit ){
				delete lit[ name ];
			}
			else	{
				delete window[ name ];
			}
		}
		catch(e){
			if( lit ){
				lit[ name ] = undefined;
			}
			else	{
				window[ name ] = undefined;
			}
		}
	}

};