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



    for (var i in place.address_components) {
        var component = place.address_components[i];
        for (var j in component.types) {  // Some types are ["country", "political"]

                if(component.types[j]=='locality'){
                    var city = component.long_name;
                    var city_option = city.toLowerCase().trim();
                    //$("#location_add_city").val('Assérac 44410');
                    //$('.'+city_option).setAttribute("aria-selected", true);
                    //aria-selected to true

                }



            var type_element = document.getElementById(component.types[j]);
            if (type_element) {
                type_element.value = component.long_name;

            }
        }
    }


}

google.maps.event.addDomListener(window, 'load', function() {
    initializeAutocomplete('location_add_search');
});