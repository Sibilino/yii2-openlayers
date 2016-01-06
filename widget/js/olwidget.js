if (typeof sibilino === "undefined") {
    sibilino = {}; // Initialize sibilino as empty object if not existing
}
sibilino.olwidget = (function ($) {
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        // Storage for additional options that may be set by other javascript scripts
        // Usage: sibilino.olwidget.mapOptions[mapId] = { layers: [new ol.layer.Vector(...)] }
        // Then, configure the OpenLayers widget with "id" equal to mapId
        mapOptions: {},
        // Create the map with the options coming from PHP as the "options" argument.
        // Options will be merged with mapOptions[id], if existing.
        createMap: function (options, id) {
            if (typeof id === "undefined") {
                id = options['target'];
            }
            if (pub.mapOptions[id]) {
                $.extend(true, options, pub.mapOptions[id]); // Deep merge of mapOptions into options
            }
            pub.maps[id] = new ol.Map(options);
        },
        maps: {},
        getMapById: function (id) {
            return pub.maps[id];
        }
    };
    
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(sibilino);
});
