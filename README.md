OpenLayers 3 Widget for Yii 2
===============================
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
		'@sibilino/yii2/openlayers' => '@vendor/sibilino/yii2/openlayers/widget',
		//...
	],
	//...
]
```

Remember to use the namespace `sibilino\yii2\openlayers` when you calling any of the widget's classes:
```php
use sibilino\yii2\openlayers\OpenLayers;
use sibilino\yii2\openlayers\OL;
```

# Usage
--------
In your view, echo the result of passing your configuration array to the widget method, as usual. This configuration array will then be encoded in JSON in order to be passed as options to the OpenLayers Map(). For example:
```php
use sibilino\yii2\openlayers\OpenLayers;
use sibilino\yii2\openlayers\OL;
//...

echo OpenLayers::widget([
	'id' => 'test',
	'mapOptions' => [
		'layers' => [
			new OL('layer.Tile', [
				'source' => new OL('source.MapQuest', [
					'layer' => 'sat',
				]),
			]),
		],
		'view' => [
			'center' => new JsExpression('ol.proj.transform([37.41, 8.82], "EPSG:4326", "EPSG:3857")'),
			'zoom' => 4,
		],
	],
]);?>
```
As usual, whenever a JavaScript expression must be passed as a configuration option, a JsExpression object is needed. However, this widget offers the OL class to easily specify OpenLayers objects in the configuration.

For details on the OL class, read the next section.
 
For details and examples on OpenLayers configuration, see [the official OpenLayers 3 documentation] (http://openlayers.org/).

## Specifying OpenLayers "ol." objects using the OL class
---------------------------------------------------------
Many of the OpenLayers options must be specified by an instance of a JavaScript object under the "ol" namespace. This would traditionally require a JsExpression with a string such as `new ol.layer.Tile()` (for a Tile object), with further complications to pass configuration to the constructed object.
To avoid this cumbersome notation, the OL class can be used. Its constructor accepts a classname, which can include namespace information, and an array of options for the classname's constructor. For example:
```php
$olObject = new OL('source.MapQuest', ['layer' => 'sat']);
``` 
Each OL object behaves as a JsExpression that will generate the JavasCript code to instantiate the specified classname with the options. In the case of the example, the resulting code would be:
```php
new ol.source.MapQuest({layer:"sat"})
```
 