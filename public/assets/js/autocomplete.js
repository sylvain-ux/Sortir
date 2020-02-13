function initializeAutocomplete(id) {
    var element = document.getElementById(id);
    if (element) {
        var autocomplete = new google.maps.places.Autocomplete(element, { types: ['geocode'] });
        google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);
    }
}

function onPlaceChanged() {
    var place = this.getPlace();

     let latitude = place.geometry.location.lat();
     let longitude = place.geometry.location.lng();
    $('#location_add_longitude').val(longitude);
    $('#location_add_latitude').val(latitude);

console.log(place);

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


    $("#location_add_city").val(city);
    $("#location_add_street").val(route);

}

google.maps.event.addDomListener(window, 'load', function() {
    initializeAutocomplete('location_add_search');
});