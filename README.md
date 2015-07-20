OpenLayers 3 Widget for Yii 2
===============================
[![Build Status](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Sibilino/yii2-openlayers/?branch=master)

This widget encapsulates the [OpenLayers 3] (http://openlayers.org/) library for easy use in Yii 2.

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
The idea is that the javascript options for OpenLayers will be directly translated to a PHP array, using `new OL('something')` when the javascript requires `new ol.something()`.

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

## Configuration
----------------
The widget supports the following configuration options (to be used outside of the mapOptions array):
* `id`: The id for the widget and the generated container div.
* `options`: Array of HTML options for the container div.
* `jsVarName`: The name of the JavaScript variable that will receive the Map object upon construction.
* `scriptPosition`: The position where the widget will register the map generation code (`View::POS_END` by default). Note that the OpenLayers _library_ will always be registered in `View::POS_HEAD`.
* `mapOptions`: The configuration array to be passed to the JavaScript OpenLayers Map() constructor. Its structure and available options are the same that are supported by the [OpenLayers 3 library] (http://openlayers.org/). Some simplifications are supported by the widget, as described in the next section.

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
```javascript
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