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
	
	public $mapOptions = [];
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
		$id = $this->options['id'];
		
		if (isset($this->mapOptions['view']))
			$this->mapOptions['view'] = new JsExpression('new ol.View('.Json::encode($this->mapOptions['view']).')');
		if (isset($this->mapOptions['layers']))
		{
			$encodedLayers = [];
			foreach ($this->mapOptions['layers'] as $type => $source)
			{
				if (is_string($type))
				{
					$encodedLayers []= new JsExpression("new ol.layer.$type({source: new ol.source.$source()})");
				}
			}
			$this->mapOptions['layers'] = $encodedLayers;
		}		
		return "
			var map = new ol.Map({
				".Json::encode($this->mapOptions)."
			});
		";
	}
}