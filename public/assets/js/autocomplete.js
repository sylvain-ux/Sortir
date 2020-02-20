function initializeAutocomplete(id) {
    var element = document.getElementById(id);
    if (element) {
        var autocomplete = new google.maps.places.Autocomplete(element, { types: ['geocode','establishment'] });
        google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);

    }
}

// Initialize and add the map
function initMap(lat,lng) {
    var uluru = {lat: lat, lng: lng};
    // The map, centered at Uluru
    var map = new google.maps.Map(
        document.getElementById('map'), {
            zoom: 12,
            center: uluru
        });
    // The marker, positioned at Uluru
    var marker = new google.maps.Marker({position: uluru, map: map});
}

function onPlaceChanged() {
    var place = this.getPlace();

     let latitude = place.geometry.location.lat();
     let longitude = place.geometry.location.lng();
    $('.search_lng').val(longitude);
    $('.search_lat').val(latitude);

    for (var i in place.address_components) {
        var component = place.address_components[i];
        for (var j in component.types) {  // Some types are ["country", "political"]
            if(component.types[j]=='postal_code'){
                var zipcode = component.long_name;
/*
                $('.zip_44340').prop("selected");
                $("#location_add_city option[class="]").prop("selected", "selected")*/
            }
            if(component.types[j]=='locality'){
                var city = component.long_name;
            }
            if(component.types[j]=='route'){
                var route = component.long_name;
            }


            var type_element = document.getElementById(component.types[j]);
            if (type_element) {
                type_element.value = component.long_name;

            }
        }
    }


    $(".search_city").val(city);
    $(".search_street").val(route);
    $(".search_zip").val(zipcode);


   // $('#location_add_search').blur(function() {
/*        var lng = $('#location_add_longitude').val();
        var lat = $('#location_add_latitude').val();*/
    var lng = $('.search_lng').val();
    var lat = $('.search_lat').val();
        initMap(parseFloat(lat),parseFloat(lng));
    //});


}

google.maps.event.addDomListener(window, 'load', function() {
    initializeAutocomplete('location_add_search');
    initializeAutocomplete('trip_add_search');
    initializeAutocomplete('trip_location_search');

});