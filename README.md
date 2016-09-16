OpenLayers 3 Widget for Yii 2
===============================
[![Build Status](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/?branch=master)
[![Total Downloads](https://poser.pugx.org/sibilino/yii2-openlayers/downloads)](https://packagist.org/packages/sibilino/yii2-openlayers)

# Changelog for v2
------------------
Version 2 of this widget is not fully compatible with version 1.
- Removed `jsVarName` property.
- Removed `scriptPosition` property.
- Added JS module.
- Added `mapOptionScript` property.
- Minor bug fixes.

# Overview
----------
This widget encapsulates the [OpenLayers 3] (http://openlayers.org/) library for easy use in Yii 2. It automatically registers the OpenLayers library and creates a map on the target div.

The widget also facilitates defining the complex configuration options for a map, featuring:
* Shortcut mechanisms to translate your PHP structures into JavaScript.
* Handling of extra JavaScript code to be applied to the map.

# Installation
---------------

## Composer
This is the preferred way of installing the widget. Just add `sibilino/yii2-openlayers` to the `composer.json` file of your Yii 2 application and perform a Composer Update as usual.
```json
"require": {
	"sibilino/yii2-openlayers": "*"
}
```

## Manually

If for some reason you cannot or do not want to use [Composer](https://getcomposer.org/ "Composer"), then you must create the widget folder manually, and then configure your Yii 2 application to autoload the widget classes.

First, create the folder structure `sibilino/yii2-openlayers/widget` inside the `vendor` subfolder of your Yii 2 application.

Then, download the widget .zip file and extract the **contents** of its `widget` subfolder into the folder you created in the previous step.

Next, edit your application's _config_ file (usually `config/main.php` or `config/web.php`) and add the following alias:
```php
[
	//...
	'aliases' => [
		//...
		'@sibilino/yii2/openlayers' => '@vendor/sibilino/yii2-openlayers/widget',
		//...
	],
	//...
]
```

Remember to use the namespace `sibilino\yii2\openlayers` when calling any of the widget's classes:
```php
use sibilino\yii2\openlayers\OpenLayers;
use sibilino\yii2\openlayers\OL;
```

# Usage
--------
In your view, echo the widget method as usual. The options for the OpenLayers Map() can be specified in `mapOptions`.
The widget will automatically publish the OpenLayers library and output the div that will receive the map.

# Configuration
----------------
The widget supports the following configuration options (actual OpenLayers.js options go in the `mapOptions` array):
* `id`: The id for the widget and the generated container div.
* `options`: Array of HTML options for the container div.
* `mapOptions`: The configuration array to be passed to the JavaScript OpenLayers Map() constructor. The `target` option is handled automatically if not specified. Its structure and available options are the same that are supported by the [OpenLayers 3 library] (http://openlayers.org/). Some simplifications are supported, as described below.
* `mapOptionScript`: Url of a JavaScript file to be registered after the *olwidget.js* module. Can be array, to register multiple scripts. Scripts can register options for a map with id `mapId` by setting them in `sibilino.olwidget.mapOptions[mapId]`. See below for details.

# Specifying map options
------------------------
The problem with OpenLayers map options is that they require complex JavaScript structures. Two approches are available:

1. Managing your configuration in PHP and then translating it to JavaScript.
2. Managing your configuration directly in JavaScript.

If your map configuration is relatively simple and depends mostly on data structures that already exist in your PHP application, approach #1 will probably be easier. This widget contains the OL class that can facilitate PHP to JavaScript translation. See below for details.

When your configuration begins getting complex, the OL class begins to show limitations and you must use JsExpressions, which are no longer easy to nest and basically mean you are again writing plain JavaScript. In this case, approach #2 is probably necessary. This widget provides a JavaScript module that easily passes your configuration from a script file to the created map. See below for details.

Mixing up both approaches can be the best way to easily define your map configuration.

## Map options as PHP array
-----------------------
The idea is to define the JavaScript options for OpenLayers as a PHP array, using `new OL('something')` when the JavaScript requires `new ol.something()`, and let the widget manage the translation to JavaScript automatically.

For example:
```php
use sibilino\yii2\openlayers\OpenLayers;
use sibilino\yii2\openlayers\OL;
use yii\web\JsExpression;
//...

echo OpenLayers::widget([
	'id' => 'test',
	'mapOptions' => [
		'layers' => [
			// Easily generate JavaScript "new ol.layer.Tile()" using the OL class
			new OL('layer.Tile', [
				'source' => new OL('source.MapQuest', [
					'layer' => 'sat',
				]),
			]),
		],
		// Using a shortcut, we can skip the OL('View' ...)
		'view' => [
			// Of course, the generated JS can be customized with JsExpression, as usual
			'center' => new JsExpression('ol.proj.transform([37.41, 8.82], "EPSG:4326", "EPSG:3857")'),
			'zoom' => 4,
		],
	],
]);?>
```
For configuration details, read the next section.
 
For details and examples on OpenLayers configuration, see [the official OpenLayers 3 documentation] (http://openlayers.org/).

### Simplified mapOptions
-------------------------
#### Passing JavaScript and the OL class
----------------------------------------
Many of the OpenLayers options must be specified by an instance of a JavaScript object under the "ol" namespace. This would traditionally require a JsExpression with a string such as `new ol.layer.Tile()` (for a Tile object), with further complications to pass configuration to the constructed object.
To avoid this cumbersome notation, the OL class can be used. Its constructor accepts a classname, which can include namespace information, and an array of options for the classname's constructor. For example:
```php
$olObject = new OL('source.MapQuest', ['layer' => 'sat']);
``` 
Each OL object behaves as a JsExpression that will generate the JavasCript code to instantiate the specified classname with the options. In the case of the example, the resulting code would be:
```JavaScript
new ol.source.MapQuest({layer:"sat"})
```
In the end, this allows the PHP configuration array to be created just like the desired JavaScript configuration object, but using `new OL('Something')` whenever `new ol.Something()` is required.
#### Specifying options using shortcut strings
----------------------------------------------
When specifying the `mapOptions['view']` or `mapOptions['layers']` arrays, you can identify the some objects by specifying them with a string, instead of creating the corresponding OL instance.
The options that support such a string shortcut are `mapOptions['view']` and any layer in `mapOptions['layers']`. For example:
```php
'mapOptions' => [
	'layers' => [
		'Tile' => [ // The layer type as a string, no need for new OL('layer.Tile' ...)
			'source' => new OL('source.MapQuest', [
				'layer' => 'sat',
			])
		],
	],
	'view' => [ // The 'view' option does not require new OL('View' ...) either
		'center' => new JsExpression('ol.proj.transform([37.41, 8.82], "EPSG:4326", "EPSG:3857")'),
		'zoom' => 4,
	],
],
```
In addition, whenever a layer has been defined using a type string, the source can _also_ be specified using a type string. For example:
```php
'mapOptions' => [
	'layers' => [
		// Again no need for OL('ol.source.OSM'), but no configuration can be passed to the OSM object in this case.
		'Tile' => 'OSM',
	],
],
```

## Map options as JavaScript
----------------------------
The widget publishes a JavaScript module that is exposed in the global scope as `sibilino.olwidget`. Options for the creation of the map with id `mapId` can be specified as an object in the `sibilino.olwidget.mapOptions` array, associated with the `mapId` key. For example:
```js
var select = new ol.interaction.Select({...});
sibilino.olwidget.mapOptions['mainMap'] = {
    layers: [
    	new ol.layer.Vector({...})
    ],
    interactions: ol.interaction.defaults().extend([select])
}
```
You can register this kind of script by setting its web-accessible URL in the `mapOptionScript` property of the PHP widget. For example:
```php
echo OpenLayers::widget([
    'id' => 'mainMap',
    'mapOptionScript' => '@web/js/yourscript.js',
    'mapOptions' => [
        // Put your PHP-generated options here.
        // These options will be merged with the ones in yourscript.js.
        // For example:
        'layers' => [
                'Tile' => [
                    'source' => new OL('source.MapQuest', [
                        'layer' => $selectedLayer,
                    ]),
                ],
                'Vector' => [
                    'source' => new OL('source.Cluster', [
                        'distance' => 30,
                        'source' => new OL('source.Vector', [
                            'features' => $features,
                        ]),
                    ]),
                ],
        ],
        // ...
    ],
    //...
]);
```
Alternatively, you can access the `sibilino.olwidget` module from any JavaScript code that is loaded *after* the module script. To ensure proper script order, you can use a dependency to `sibilino\yii2\openlayers\OLModuleBundle`. For example:
```php
$view->registerJsFile($script, ['depends' => OLModuleBundle::className()]);
```
### Accessing the map object
If you have JavaScript code that needs to work with the map object created by the widget in your PHP code, you can find it using `sibilino.olwidget.getMapById(mapId)`. This function returns the map object created by the widget with the id `mapId`. For example:
```js
// Assuming the PHP widget was given the id "mainMap"
var map = sibilino.olwidget.getMapById("mainMap");
```
