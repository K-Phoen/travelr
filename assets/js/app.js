const L = require('leaflet');
require('leaflet.markercluster');
require('./Leaflet.Photo');

const xhr = require('xhr');

const map = L.map('map');

L.tileLayer(MAP_TILE_LAYER_URL, {
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: MAP_API_KEY
}).addTo(map);

map.zoomControl.setPosition('bottomright');

const photoLayer = L.photo.cluster().on('click', function (evt) {
    evt.layer.bindPopup(L.Util.template('<a href="data/{slug}/"><img src="{url}"/><h3>{title}</h3></a><p>{caption}</p>', evt.layer.photo), {
        className: 'leaflet-popup-photo',
        minWidth: 400
    }).openPopup();
});

xhr({
    method: 'GET',
    uri: './albums.json'
}, function (err, response, body) {
    if (err || response.statusCode !== 200) {
        return;
    }

    const photos = JSON.parse(body).map(function (photo) {
        return {
            lat: photo.latitude,
            lng: photo.longitude,
            title: photo.title,
            slug: photo.slug,
            caption: photo.description,
            url: photo.thumbnail,
            thumbnail: photo.thumbnail
        };
    });

    photoLayer.add(photos).addTo(map);
    map.fitBounds(photoLayer.getBounds());
    map.zoomOut();
});
