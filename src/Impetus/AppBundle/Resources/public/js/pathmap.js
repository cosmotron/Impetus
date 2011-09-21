var PathMap = {
    imageRoot: null,
    map: null,
    overlay: null,
    markers: [],
    nodeSpacing: {lat: 0.392, lng: 0.35},
    mapTypeOptions: {
        getTileUrl: function(coord, zoom) {
            return PathMap.getHorizontallyRepeatingTileUrl(coord, zoom,
                       function(coord, zoom) {
                           return PathMap.imageRoot + 'field_1.jpg';
                       });
        },
        tileSize: new google.maps.Size(256, 256),
        maxZoom: 10,
        minZoom: 7,
        isPng: false
    },

    init: function(imageRoot) {
        this.imageRoot = imageRoot;

        this.map = new google.maps.Map(document.getElementById('map-canvas'), {
            mapTypeControl: false,
            streetViewControl: false,
            scrollWheel: false,
        });

        var pathMapType = new google.maps.ImageMapType(this.mapTypeOptions);
        this.map.mapTypes.set('pathmap', pathMapType);
        this.map.setMapTypeId('pathmap');

        this.map.setCenter(new google.maps.LatLng(0, 0));
        this.map.setZoom(9);


        this.drawMarkers();
        this.drawPaths();
    },

    drawMarkers: function() {
        var additionMarker = new google.maps.Marker({
            position: new google.maps.LatLng((0 * this.nodeSpacing.lat),
                                             (0 * this.nodeSpacing.lng)),
            map: this.map,
            title: 'Addition 1'
        });

        var multiplicationMarker = new google.maps.Marker({
            position: new google.maps.LatLng((-0.5 * this.nodeSpacing.lat),
                                             (0 * this.nodeSpacing.lng)),
            map: this.map,
            title: 'Multiplication 1'
        });

        var subtractionMarker = new google.maps.Marker({
            position: new google.maps.LatLng((-0.5 * this.nodeSpacing.lat),
                                             (0.5 * this.nodeSpacing.lng)),
            map: this.map,
            title: 'Subtraction 1'
        });
    },

    drawPaths: function() {
        var add = new google.maps.LatLng((0 * this.nodeSpacing.lat),
                                         (0 * this.nodeSpacing.lng));
        var mul = new google.maps.LatLng((-0.5 * this.nodeSpacing.lat),
                                         (0 * this.nodeSpacing.lng));
        var sub = new google.maps.LatLng((-0.5 * this.nodeSpacing.lat),
                                         (0.5 * this.nodeSpacing.lng));

        var addToSub = [add, sub];
        var addToMul = [add, mul];

        var addToSub = new google.maps.Polyline({
            path: addToSub,
            strokeColor: '#0000ff',
            strokeOpacity: 1.0,
            strokeWeight: 1.0,
            clickable: false,
            map: this.map
        });

        var addToSub = new google.maps.Polyline({
            path: addToMul,
            strokeColor: '#808080',
            strokeOpacity: 1.0,
            strokeWeight: 1.0,
            clickable: false,
            map: this.map
        });

        /*
        var addToSub = google.maps.Polyline({
            path: [new google.maps.LatLng(),
                   new google.maps.LatLng()],
            strokeColor: '#ffff00',
            strokeOpacity: 1.0,
            strokeWeight: 5.0,
            clickable: false,
            map: this.map
        });
        */
    },

    getHorizontallyRepeatingTileUrl: function(coord, zoom, urlfunc) {
        // From http://gmaps-samples-v3.googlecode.com/svn/trunk/planetary-maptypes/planetary-maptypes.html
        var y = coord.y;
        var x = coord.x;

        // tile range in one direction range is dependent on zoom level
        // 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
        var tileRange = 1 << zoom;

        // don't repeat across y-axis (vertically)
        if (y < 0 || y >= tileRange) {
            return null;
        }

        // repeat across x-axis
        if (x < 0 || x >= tileRange) {
            x = (x % tileRange + tileRange) % tileRange;
        }

        return urlfunc({x:x,y:y}, zoom);
    }
}
