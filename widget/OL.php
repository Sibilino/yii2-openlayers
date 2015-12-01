<?php
namespace sibilino\yii2\openlayers;

use yii\web\JsExpression;
use yii\helpers\Json;

/**
 * This class makes it easier to specify OpenLayers objects as options for the OpenLayers widget, resulting in more concise PHP array structures than when using JsExpression.
 * See {@link OL::__construct} for usage examples.
 * @link https://github.com/Sibilino/yii2-openlayers
 * @copyright Copyright (c) 2015 Luis Hernández Hernández
 * @license http://opensource.org/licenses/MIT MIT
 */
class OL extends JsExpression
{
	/**
	 * Must include the full namespace path, excluding 'ol.'. For example, "layer.Tile".
	 * @var string the classname to be instantiated.
	 */
	protected $class;
	/**
	 * @var array|null the properties to be passed to the constructor of $class.
	 */
	protected $properties;
	
	/**
	 * The OL class takes a classname and an optional property array as constructor arguments. Then, when an OL object is passed to Json::encode(), the following expression will be generated:
	 * <code>new ol.$class($properties)</code>.
	 * Therefore, the need for JsExpression is eliminated, and the 'ol.' namespace, the 'new' keyword and constructor call are automatically added.
	 * For example, the following mapOptions array:
	 * <pre>//...
	 * 'layers' => [
	 *   new OL('layer.Tile', [
	 *		'source' => new OL('source.MapQuest', [
	 *			'layer' => 'sat',
	 *		]),
	 *	 ]),
	 * ],
	 * 'view' => [
	 * 	 new OL('View', [
	 * 		'center' => [0, 0],
	 * 		'zoom' => 4,
	 * 	],
	 * ]
	 * /...</pre>
	 * Will be encoded as follows:
	 * <pre>
	 * {
	 * 	"layers": [
	 * 		new ol.layer.Tile({
	 * 			"source": new ol.source.MapQuest({
	 * 				"layer": "sat"
	 * 			})
	 * 		})
	 * 	],
	 * 	"view": new ol.View({
	 * 		"center": [0, 0],
	 * 		"zoom": 4
	 * 	})
	 * }
	 * </pre>
	 * @param string $class
	 * @param mixed $properties
	 */
	public function __construct($class, $properties = null)
	{
		$this->class = $class;
		$this->properties = $properties;
		$this->expression = $this->generateExpression();
	}
	
	/**
	 * Generates the JavaScript expression that correspond with this object's $class and $properties.
	 * @return string
	 */
	protected function generateExpression()
	{
		if (isset($this->properties))
			$properties = Json::encode($this->properties);
		else
			$properties = '';
		return "new ol.$this->class($properties)";
	}

    public function __toString() {
        return $this->expression;
    }
	
}