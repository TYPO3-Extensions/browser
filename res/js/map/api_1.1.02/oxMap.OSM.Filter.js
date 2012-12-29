
 'use strict';

 window.oxMap.OSM.Filter = function( parameter ){

	var self = this,
        $filter,
        $filterItems;

	this.filterOnMap = function( event, item ){
    	var filterOn;

    	item = item || this;
    	filterOn = item.checked ? true : false;

    	oxMap.OSM.map.getLayersByName( item.name )[ 0 ].setVisibility( filterOn );
    };

    this.setup = (function(){

    	$filter = $(oxMap.OSM.configuration.filter);
    	$filterItems = oxMap.OSM.configuration.filterItems;

    	$filter.find( $filterItems ).each( function(){
    		self.filterOnMap( null, this );
    	});
    	$filter.on( 'change', $filterItems, self.filterOnMap );

    })();

};