var sibilino = {
    openlayers: (function ($) {
        var pub = {
            // whether this module is currently active. If false, init() will not be called for this module
            // it will also not be called for all its child modules. If this property is undefined, it means true.
            isActive: true,
            init: function () {

            },
            maps: {},
            // Storage for additional options that may be set by other javascript scripts
            // Usage: sibilino.openlayers.mapOptions["identifier"] = { layers: [new ol.layer.Vector(...)] }
            // Then, configure the OpenLayers widget with $jsOptionIdentifier = "identifier".
            mapOptions: {},
            createMap: function (options, id, jsOptionIdentifier) {
                if (typeof id === "undefined") {
                    id = options['target'];
                }
                if (typeof jsOptionIdentifier !== "undefined") {
                    $.extend(true, options, this.mapOptions[jsOptionIdentifier]); // Deep merge
                }
                this.maps[id] = new ol.Map(options);
            },
            getMapById: function (id) {
                return this.maps[id];
            }
        };
        // ... private functions and properties go here ...
        return pub;
    })(jQuery)
};

jQuery(document).ready(function () {
    yii.initModule(sibilino);
});