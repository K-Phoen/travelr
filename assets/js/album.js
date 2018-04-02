const Masonry = require('masonry-layout');
const imagesLoaded = require('imagesloaded');

function initGrid() {
    new Masonry('.grid', {
        itemSelector: '.grid-item',
        columnWidth: 300,
    });
}

imagesLoaded('.grid', function () {
    initGrid();
});
