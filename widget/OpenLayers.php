<?php
namespace sibilino\yii2\openlayers;

use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Yii 2 widget encapsulating OpenLayers 3 and offering simplified option specification.
 * @link https://github.com/Sibilino/yii2-openlayers
 * @copyright Copyright (c) 2015 Luis Hernández Hernández
 * @license http://opensource.org/licenses/MIT MIT
 */
class OpenLayers extends Widget
{
	/**
     * @var array the HTML attributes for the container div of this widget.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
	public $options = [];
	/**
	 * The properties to be passed to the OpenLayers Map() constructor. In order to ease passing complex javascript structures, some simplifications are supported.
	 * See {@link OpenLayers::processOptions} for details on simplified option specification.
	 * @var array
	 */
	public $mapOptions = [];
	/**
	 * @var string the naem for the JavasCript variable that will receive the Map object upon execution of the widget.
	 */
	public $jsVarName;
	/**
	 * @var int the position where the Map creation script must be inserted. Default is \yii\web\View::POS_END.
	 * @see \yii\web\View::registerJs()
	 */
	public $scriptPosition = View::POS_END;
	
	public function init()
	{
		if (!isset($this->options['id']))
			$this->options['id'] = $this->getId();
		if (!isset($this->jsVarName))
			$this->jsVarName = $this->options['id'];
		$this->mapOptions['target'] = $this->options['id'];
		OpenLayersBundle::register($this->view);
	}
	
	public function run()
	{
		$this->processOptions();
		
		$script = "var $this->jsVarName = ".Json::encode(new OL('Map', $this->mapOptions)).";";
		$this->view->registerJs($script, $this->scriptPosition);
		
		return Html::tag('div', '', $this->options);
	}
	
	/**
	 * Checks whether several "complex" options have been specified as a key => value pair where key is a string.
	 * If found, those properies will be automatically turned into the JavaScript expression that instantiates the corresponding OpenLayers object, thus eliminating the need to manually create a JsExpression object.
	 * The value for these processed keys will be passed as options to the constructor of the OpenLayers object.
	 * Supported simplified properties are:
	 * <ul>
	 * <li><b>view</b>: Can be specified as 'view' => $optionArray.
	 * For example, <code>['view' => ['center'=>[0,0],'zoom'=>2]]</code> will produce this JS: <code>new ol.View({"center":[0,0], "zoom":2})</code>
	 * <li><b>layers</b>: Each layer in the array can be specified as $type => $options, where $type is a string.
	 * For example, <code>['layers' => ['Tile' => ['visible' => false]</code> will produce this JS: <code>new ol.layer.Tile({"visible": false})</code>
	 * <li><b>layer sources</b>: In addition, when a layer's $type => $option pair are both strings, the $option is considered the layer's "source", and will also be converted to the corresponding object.
	 * For example, <code>['layers' => ['Tile' => 'OSM']</code> will produce this JS: <code>new ol.layer.Tile({"source": new ol.source.OSM()})</code>
	 * </li>
	 * </ul>
	 * If these simplifications are not enough to avoid using complex JsExpression structures, make sure to see the {@link OL} class for an abbreviated way of specifying OpenLayers object instances.
	 * @see OL
	 */
	protected function processOptions()
	{
		if (isset($this->mapOptions['view']) && is_array($this->mapOptions['view']))
			$this->mapOptions['view'] = new OL('View', $this->mapOptions['view']);
		
		if (isset($this->mapOptions['layers']))
			$this->processSimplifiedLayers();
	}
	
	/**
	 * Takes each key => value pair in $this->mapOptions['layers'] and checks if key is a string. In that case, a new Layer OL object will created for this layer.
	 * Then, if the value is also a string, the corresponding Source OL object is created as well. 
	 */
	protected function processSimplifiedLayers()
	{
		$processedLayers = [];
		foreach ($this->mapOptions['layers'] as $type => $options)
		{
			if (is_string($type))
			{
				if (is_string($options))
					$options = ['source' => new OL("source.$options")];
					
				$processedLayers []= new OL("layer.$type", $options);
			}
			else // Therefore $type is simply an integer array key
				$processedLayers []= $options;
		}
		$this->mapOptions['layers'] = $processedLayers;
	}
}
