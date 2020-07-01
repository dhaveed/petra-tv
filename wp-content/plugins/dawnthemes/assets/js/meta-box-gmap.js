google.maps.event.addDomListener(window, 'load', function(){
	var	map,
		infowindow,
		autocomplete,
		geocoder = new google.maps.Geocoder(),
		directionsDisplay = new google.maps.DirectionsRenderer({draggable: true}),
    	directionsService = new google.maps.DirectionsService();

    var options = {
        center: new google.maps.LatLng(DTGmapSetting.lat,DTGmapSetting.lng),
        zoom: 11,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('meta_box_google_map'), options);
    var input = document.getElementById('meta_box_google_map_search_input');
    autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });

    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("route"));

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });


    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(DTGmapSetting.lat,DTGmapSetting.lng),
        draggable: true
    });

    google.maps.event.addListener(map, 'click', function(e) {
        marker.setPosition(e.latLng);
        var c_lat  = marker.position.lat(),
         	c_lng  = marker.position.lng();

            jQuery('#_dt_map_lat').val( c_lat );            
            jQuery('#_dt_map_lng').val( c_lng );
            geocoder.geocode( {'latLng': e.latLng}, function(results, status) {
                if( status == google.maps.GeocoderStatus.OK ) {
                    if(results[0]) {
                        var address = results[0].formatted_address;
                        jQuery('#_dt_address').val( address );
                        infowindow.setContent(
                                '<strong>' + address + '</strong><br />' +
                                '<b>Latitude:</b> ' + c_lat + '<br />' +
                                '<b>Longitude:</b> ' + c_lng
                        );

                        infowindow.open(map, marker);
                    }
                }
            });

    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
     
        if (place) {
            if (place.geometry) {
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                marker.setPosition(place.geometry.location);
                var address = '';
                if (place.address_components) {
                    address = [(place.address_components[0] &&
                                place.address_components[0].short_name || ''),
                        (place.address_components[1] &&
                                place.address_components[1].short_name || ''),
                        (place.address_components[2] &&
                                place.address_components[2].short_name || '')
                    ].join(', ');
                }

                infowindow.setContent('<strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);
                document.getElementById('_dt_address').value = place.name;
                document.getElementById("_dt_map_lat").value = place.geometry.location.lat();
                document.getElementById("_dt_map_lng").value = place.geometry.location.lng()

            }
        }
    });

    google.maps.event.addListener(map, "idle", function() {
        google.maps.event.trigger(map, 'resize');
    });
});
jQuery(document).ready(function(){
	jQuery('#meta_box_google_map_search_input').bind('keypress keydown keyup', function(e){
	    if(e.keyCode == 13) { e.preventDefault(); }
	});
});
