<?php
namespace sibilino\yii2\openlayers;

use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Yii 2 widget encapsulating OpenLayers 3
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
	 * The properties to be passed to the OpenLayers Map() constructor. The following special properties are supported:
	 * <ul>
	 * <li><b>view</b>: Array of properties to be passed to an OpenLayers View() constructor.
	 * This means that a view can be specified without the need for a JsExpression object only to call the View constructor.
	 * Example usage: <code>
	 * 'view' => [
	 *     'center' => new JsExpression('ol.proj.transform([37.41, 8.82], "EPSG:4326", "EPSG:3857")'),
	 *     'zoom' => 4,
	 * ],
	 * </code></li>
	 * <li><b>layers</b>: A simplified syntax is supported for this option, where layers can be specified as type => source string pairs.
	 * For example: <code>
	 * 'layers' => [
	 *     'Tile' => 'OSM',
	 *	],
	 * </code></li>
	 * </ul> 
	 * @var array
	 */
	public $mapOptions = [];
	public $jsVarName = 'map';
	/**
	 * @var int the position where the Map creation script must be inserted. Default is \yii\web\View::POS_END.
	 * @see \yii\web\View::registerJs()
	 */
	public $scriptPosition = View::POS_END;
	
	public function init()
	{
		if (!isset($this->options['id']))
			$this->options['id'] = $this->getId();
		$this->mapOptions['target'] = $this->options['id'];
		OpenLayersBundle::register($this->view);
	}
	
	public function run()
	{
		$this->view->registerJs($this->getInitScript(), $this->scriptPosition);
		
		return Html::tag('div', '', $this->options);
	}
	
	/**
	 * Generates the creation script for the OpenLayers map with the current configuration.
	 * @return string
	 */
	protected function getInitScript() {		
		$this->processSimplifiedOptions();
		return "var $this->jsVarName = ".Json::encode(new OL('Map', $this->mapOptions));
	}
	
	protected function processSimplifiedOptions()
	{
		if (isset($this->mapOptions['view']) && is_array($this->mapOptions['view']))
			$this->mapOptions['view'] = new OL('View', $this->mapOptions['view']);
		
		if (isset($this->mapOptions['layers']))
		{
			$processedLayers = [];
			foreach ($this->mapOptions['layers'] as $type => $source)
			{
				if (!is_string($type))
					$processedLayers [$type]= $source; // Unmodified
				else
				{
					if (is_string($source))
					{
						$source = new OL("source.$source");
					}
					unset($this->mapOptions['layers'][$type]);
					$processedLayers []= new OL("layer.$type", ['source' => $source]);
				}					
			}
			$this->mapOptions['layers'] = $processedLayers;
		}
	}
}