if (typeof sibilino === "undefined") {
    sibilino = {}; // Initialize sibilino as empty object if not existing
}
sibilino.openlayers = (function ($) {
    var pub = {
        isActive: true,
        init: function () {
            // Nothing to init
        },
        // Storage for additional options that may be set by other javascript scripts
        // Usage: sibilino.openlayers.mapOptions["identifier"] = { layers: [new ol.layer.Vector(...)] }
        // Then, configure the OpenLayers widget with $jsOptionIdentifier = "identifier".
        mapOptions: {},
        createMap: function (options, id, jsOptionIdentifier) {
            if (typeof id === "undefined") {
                id = options['target'];
            }
            if (typeof jsOptionIdentifier !== "undefined") {
                $.extend(true, options, mapOptions[jsOptionIdentifier]); // Deep merge
            }
            maps[id] = new ol.Map(options);
        },
        maps: {},
        getMapById: function (id) {
            return maps[id];
        }
    };
    // ... private functions and properties go here ...
    return pub;
})(jQuery);

jQuery(document).ready(function () {
    yii.initModule(sibilino);
});
