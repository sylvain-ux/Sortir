$(document).ready(function(){
    //Mapbox
    $(function() {
        var $map = $('#mapLoc');
        if($map.length!=1){
            return;
        }
        mapboxgl.accessToken = 'pk.eyJ1IjoiZ3VpbGxhdW1ld2ViY29uc2VpbCIsImEiOiJjam41dGd1bTMwNTR1M3BueHp3ZGh0NTl0In0.Fo2AoODNvogaYQnb7vEYxg';
        var map = new mapboxgl.Map({
            container: $map.attr('id'),
            style: 'mapbox://styles/avecmotspourmaux/cjuv7otgp16wd1fqowl1y5vbo',
            //style: 'mapbox://styles/mapbox/streets-v11',
            //center: [$map.data('long'),$map.data('lat')],
            center: [-0.729044, 48.07938],
            zoom: 6.2,
        });
        //$('.info_map').hide();
        map.addControl(new mapboxgl.NavigationControl(), 'top-left');
        //map.scrollZoom.disable();
        map.on("load", function () {
            geojson.data.forEach(function (marker) {

                // create a HTML element for each feature
                var el = document.createElement('div');
                el.className = 'marker';
                //var html = '<h3 class="h3 title">'+marker.properties.title+'</h3><p class="">'+marker.properties.description+'</p><p>'+marker.properties.type+'</p>';
                var myPopup = new mapboxgl.Popup({
                    offset: 25,
                });

                myPopup.on('open', function(){
                    //console.log(marker.properties.link_spot);
                    //$('.info_map').html(html);

                    $('.info_map #info_map_title').html('Recevez des informations <br>sur notre parternaire <br><strong>'+marker.properties.title+'</strong>');
                    $('.mapboxgl-marker').removeClass("active");
                    $('.info_map').show();
                    el.classList.add("active");
                    $('.info_map #country').val(''+marker.properties.title+'');

                });
                //replace info_map content


                // make a marker for each feature and add to the map
                new mapboxgl.Marker(el)
                    .setLngLat(marker.geometry.coordinates)
                    .setPopup(myPopup)
                    .addTo(map);

            });
            $( ".info_map .close" ).click(function() {
                $('.info_map').hide();
            });
        });


    });
});