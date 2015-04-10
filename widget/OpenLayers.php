<?php
namespace sibilino\yii2\openlayers;

use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;

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
	 * @var int the position where the Map creation script must be inserted. Default is \yii\web\View::POS_END.
	 * @see \yii\web\View::registerJs()
	 */
	public $scriptPosition = View::POS_END;
	
	public function init()
	{
		OpenLayersBundle::register($this->view);
		if (!isset($this->options['id']))
			$this->options['id'] = $this->getId();
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
		return "
			var map = new ol.Map({
				target: '$id',
				layers: [
					new ol.layer.Tile({
						source: new ol.source.MapQuest({layer: 'sat'})
					})
				],
				view: new ol.View({
					center: ol.proj.transform([37.41, 8.82], 'EPSG:4326', 'EPSG:3857'),
					zoom: 4
				})
			});
		";
	}
}