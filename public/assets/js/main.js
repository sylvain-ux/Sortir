//navbar & anchors

    $('a[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 120
                }, 1000);
                return false;
            }
        }
    });


    function navbar() {
        var distanceY = window.pageYOffset || document.documentElement.scrollTop,
            //menu
            menu_pos = 100,
            menu = $('#main_navbar');
        if (distanceY > menu_pos) {
            $('#main_navbar').addClass('smaller');
        } else {
            $('#main_navbar').removeClass('smaller');
        }
        //console.log(distanceY);
    }

    window.onload = function () {
        navbar();
        window.addEventListener('scroll', navbar);
    }


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
                //console.log(marker.properties.link.toString());
                var el = document.createElement('div');
                el.className = 'marker-' + marker.properties.markersymbol;
// make a marker for each feature and add to the map
                new mapboxgl.Marker(el)
                    .setLngLat(marker.geometry.coordinates)
                    .setPopup(new mapboxgl.Popup({
                        className: 'test',
                    }) // add popups

                        .setHTML('<h5>'+marker.properties.title+'</h5><p>'+marker.properties.title_location+'</p><p class="text-center"><a href="http://localhost/sortir/public/trip/detail/'+marker.properties.id+'" class="btn btn-primary btn-sm">En savoir +</a></p>'))
                    .addTo(map);
            });


            $( ".info_map .close" ).click(function() {
                $('.info_map').hide();
            });
        });


    });

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
                    //console.log(marker.properties.link.toString());
                    var el = document.createElement('div');
                    el.className = 'marker-' + marker.properties.markersymbol;
// make a marker for each feature and add to the map
                    new mapboxgl.Marker(el)
                        .setLngLat(marker.geometry.coordinates)
                        .setPopup(new mapboxgl.Popup({
                            className: 'test',
                        }) // add popups

                            .setHTML('<h5>'+marker.properties.title+'</h5><p>'+marker.properties.title_location+'</p><p class="text-center"><a href="http://localhost/sortir/public/trip/detail/'+marker.properties.id+'" class="btn btn-primary btn-sm">En savoir +</a></p>'))
                        .addTo(map);
                });


                $( ".info_map .close" ).click(function() {
                    $('.info_map').hide();
                });
            });


        });

});

$(document).ready(function(){
    //Mapbox
    $(function() {
        var $map = $('#adminMap');
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
                //console.log(marker.properties.link.toString());
                var el = document.createElement('div');
                el.className = 'marker-' + marker.properties.markersymbol;
// make a marker for each feature and add to the map
                new mapboxgl.Marker(el)
                    .setLngLat(marker.geometry.coordinates)
                    .setPopup(new mapboxgl.Popup({
                        className: 'test',
                    }) // add popups

                        .setHTML('<h5>'+marker.properties.title+'</h5><p>'+marker.properties.title_location+'</p><p class="text-center"><a href="http://localhost/sortir/public/admin/trip/update/'+marker.properties.id+'" class="btn btn-primary btn-sm">modifier</a></p>'))
                    .addTo(map);
            });


            $( ".info_map .close" ).click(function() {
                $('.info_map').hide();
            });
        });


    });

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
                //console.log(marker.properties.link.toString());
                var el = document.createElement('div');
                el.className = 'marker-' + marker.properties.markersymbol;
// make a marker for each feature and add to the map
                new mapboxgl.Marker(el)
                    .setLngLat(marker.geometry.coordinates)
                    .setPopup(new mapboxgl.Popup({
                        className: 'test',
                    }) // add popups

                        .setHTML('<h5>'+marker.properties.title+'</h5><p>'+marker.properties.title_location+'</p><p class="text-center"><a href="http://localhost/sortir/public/trip/detail/'+marker.properties.id+'" class="btn btn-primary btn-sm">En savoir +</a></p>'))
                    .addTo(map);
            });


            $( ".info_map .close" ).click(function() {
                $('.info_map').hide();
            });
        });


    });

});

// external js: isotope.pkgd.js
// init Isotope
// store filter for each group
$(document).ready(function(){
    var filters = {};
    var filterValue = filteredValue = '';
    // quick search regex
    var qsRegex;
    var buttonFilter;

    $('.select_filter').each( function(){
        filteredValue = getFiltersValue($(this));
    });
    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
        masonry: {
//columnWidth: 360,
//gutter: 15
        },
        //filter: filteredValue,
        filter: function() {
            var $this = $(this);
            var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
            var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
            return searchResult && buttonResult;
        }
    });
    $('.select_filter').on( 'change', function(event) {
        var $select = $( event.currentTarget );
        /*                filteredValue = getFiltersValue($select);
                        console.log(filteredValue);*/
        // set filter for Isotope

        buttonFilter = getFiltersValue($select);
        //console.log($( this ));
        $grid.isotope();
        var elems = $grid.isotope('getFilteredItemElements');
        /*                $('.grid-item').removeClass('first_elem second_elem third_elem');
                        $(elems[0]).addClass('first_elem');
                        $(elems[1]).addClass('second_elem');
                        $(elems[2]).addClass('third_elem');*/
    });

    // use value of search field to filter
    var $quicksearch = $('.quicksearch').keyup( debounce( function() {
        qsRegex = new RegExp( $(this).val(), 'gi' );
        //console.log($(this).val());
        $grid.isotope();

    }) );
    // debounce so filtering doesn't happen every millisecond
    function debounce( fn, threshold ) {
        var timeout;
        threshold = threshold || 100;
        return function debounced() {
            clearTimeout( timeout );
            var args = arguments;
            var _this = this;
            function delayed() {
                fn.apply( _this, args );
            }
            timeout = setTimeout( delayed, threshold );
        };
    }

    function getFiltersValue(elem){
        var $buttonGroup = elem.parents('.filters-button-group');
        var filterGroup = $buttonGroup.attr('data-filter-group');
        filters[ filterGroup ] = elem.val();
        filterValue = concatValues( filters );
        console.log(filterValue);
        return filterValue;
    };
    // flatten object by concatting values
    function concatValues( obj ) {
        var value = '';
        for ( var prop in obj ) {
            value += obj[ prop ];
        }
        return value;
    }

    $('.grid').imagesLoaded( function() {
        // images have loaded
    });

});


// Fonction permettant de changer le background en fonction de l'heure actuelle
$(document).ready(function(){
    var d = new Date();
    var n = d.getHours();
    if (n > 19 || n < 6)
        // If time is after 7PM or before 6AM, apply night theme to ‘body’
        $('body').addClass('night');
    else if (n > 12 && n < 19)
        // If time is between 4PM – 7PM sunset theme to ‘body’
        $('body').addClass('sunset');
    else
        // Else use ‘morning’ theme
        $('body').addClass('morning');
});