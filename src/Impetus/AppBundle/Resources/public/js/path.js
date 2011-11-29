var PathMap = {
    imageRoot: null,
    map: null,
    markers: [],
    nodes: {},
    nodeSpacing: {lat: 0.392, lng: 0.35},
    overlay: null,

    mapTypeOptions: {
        getTileUrl: function(coord, zoom) {
            return PathMap.getHorizontallyRepeatingTileUrl(coord, zoom,
                       function(coord, zoom) {
                           return PathMap.imageRoot + 'field_1.jpg';
                       });
        },
        isPng: false,
        maxZoom: 10,
        minZoom: 7,
        tileSize: new google.maps.Size(256, 256)
    },

    init: function(imageRoot) {
        this.imageRoot = imageRoot;

        this.map = new google.maps.Map(document.getElementById('map-canvas'), {
            mapTypeControl: false,
            streetViewControl: false,
            scrollwheel: false,
        });

        var pathMapType = new google.maps.ImageMapType(this.mapTypeOptions);
        this.map.mapTypes.set('pathmap', pathMapType);
        this.map.setMapTypeId('pathmap');

        this.map.setCenter(new google.maps.LatLng(-0.75, 0));
        this.map.setZoom(9);

        this.getPathNodes();

        this.drawMap();
    },

    getPathNodes: function() {
        var ajaxResponse = [];

        $.ajax({
            async: false,
            dataType: 'json',
            type: 'GET',
            url: Routing.generate('_path_nodes'),
            success: function(data) {
                ajaxResponse = eval(data);

                for (var i = 0; i < ajaxResponse.length; i++) {
                    var node = {
                        id: null,
                        name: null,
                        latLng: null,
                        prereqs: []
                    };

                    node.id = ajaxResponse[i].id;
                    node.name = ajaxResponse[i].name;
                    node.latLng = new google.maps.LatLng(
                        (ajaxResponse[i].v_pos * PathMap.nodeSpacing.lat),
                        (ajaxResponse[i].h_pos * PathMap.nodeSpacing.lng)
                    );
                    node.prereqs = ajaxResponse[i].prereqs;

                    PathMap.nodes[node.id] = node;
                }
            },
            error: function() {
                alert("There was an HTTP error getting the path map markers. Try again.");
                return;
            }
        });
    },

    drawMap: function() {
        $.each(this.nodes, function(node_id) {
            var node = PathMap.nodes[node_id];

            var marker = new com.redfin.FastMarker(
                'marker-' + node.id,
                node.latLng,
                ['<div class="pathNode">' + node.name + '</div>'],
                '',
                1,
                0,0);

            PathMap.markers.push(marker);

            PathMap.drawPrereqPaths(node);
        });

        this.drawOverlay();
    },

    drawOverlay: function() {
        // Draw the overlay containing the node markers
        this.overlay = new com.redfin.FastMarkerOverlay(this.map, this.markers);
        this.overlay.drawOriginal = this.overlay.draw;

        this.overlay.draw = function() {
            this.drawOriginal();

            var pathNodes = $('.pathNode');

            pathNodes.each(function() {
                $(this).click(function() {
                    var marker = $(this).parent().attr('id');
                    var markerId = marker.substring(marker.lastIndexOf('-') + 1);

                    window.location.href = Routing.generate('_path_show', { id: markerId });
                }).hover(function() {
                    $(this).css('cursor', 'pointer');
                });
            });
        }
    },

    drawPrereqPaths: function(node) {
        var source = node.id;

        for (var i = 0; i < node.prereqs.length; i++) {
            var target = node.prereqs[i].id;

            var endPoints = [
                this.nodes[source].latLng,
                this.nodes[target].latLng
            ];

            var path = new google.maps.Polyline({
                path: endPoints,
                strokeColor: '#0000ff',
                strokeOpacity: 1.0,
                strokeWeight: 2.0,
                clickable: false,
                map: PathMap.map
            });
        }
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
