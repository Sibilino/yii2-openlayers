var sibilino = {
    openlayers: (function ($) {
        var pub = {
            // whether this module is currently active. If false, init() will not be called for this module
            // it will also not be called for all its child modules. If this property is undefined, it means true.
            isActive: true,
            init: function () {

            },
            maps: [],
            createMap: function (properties) {
                this.maps.push(new ol.Map(properties));
            }
        };
        // ... private functions and properties go here ...
        return pub;
    })(jQuery)
};

jQuery(document).ready(function () {
    yii.initModule(sibilino);
});