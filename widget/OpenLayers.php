<?php
namespace sibilino\yii2\openlayers;

use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;

class OpenLayers extends Widget
{
	
	public $htmlOptions = [];
	
	public $scriptPosition = View::POS_END;
	
	public function init()
	{
		OpenLayersBundle::register($this->view);
		if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id'] = $this->getId();
	}
	
	public function run()
	{
		$this->view->registerJs($this->getInitScript(), $this->scriptPosition);
		
		return Html::tag('div', '', $this->htmlOptions);
	}
	
	protected function getInitScript() {
		$id = $this->htmlOptions['id'];
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