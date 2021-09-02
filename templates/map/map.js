var attribution = new ol.control.Attribution({
    collapsible: false
});

var map = new ol.Map({
    controls: ol.control.defaults({attribution: false}).extend([attribution]),
    layers: [
        new ol.layer.Tile({
            source: new ol.source.OSM({
                url: "https://a.tile.openstreetmap.org/{z}/{x}/{y}.png",
                attributions: [ol.source.OSM.ATTRIBUTION, 'Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>'],
                maxZoom: 18
            })
        })
    ],
    target: 'map',
    view: new ol.View({
        center: ol.proj.fromLonLat(mapData.mapCenter),
        maxZoom: 18,
        zoom: 12
    })
});
console.log(mapData.bullseye.blue);

var markerBullseyeBlue = new ol.Overlay({
    position: ol.proj.fromLonLat(mapData.bullseye.blue),
    positioning: 'center-center',
    element: document.getElementById('markerBullseyeBlue'),
    stopEvent: false
});
map.addOverlay(markerBullseyeBlue);

var markerBullseyeRed = new ol.Overlay({
    position: ol.proj.fromLonLat(mapData.bullseye.red),
    positioning: 'center-center',
    element: document.getElementById('markerBullseyeRed'),
    stopEvent: false
});
map.addOverlay(markerBullseyeRed);

/*
function add_map_point(lat, lng) {
    var vectorLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: [new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.transform([parseFloat(lng), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857')),
            })]
        }),
        style: new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 0.5],
                anchorXUnits: "fraction",
                anchorYUnits: "fraction",
                src: "https://upload.wikimedia.org/wikipedia/commons/e/ec/RedDot.svg"
            })
        })
    });

    map.addLayer(vectorLayer);
}
*/