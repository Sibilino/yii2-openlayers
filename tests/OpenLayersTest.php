<?php
namespace sibilino\yii2\openlayers;

use yiiunit\TestCase;
use yii\web\View;
use yii\web\JsExpression;

class OpenLayersTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->mockApplication([
			'vendorPath' => __DIR__.'/../../../..',
			'components' => [
				'assetManager' => [
					'basePath' => __DIR__.'/../../../../../assets',
					'baseUrl' => 'http://localhost/tester2/assets',
				],
			],
			'aliases' => [
				'@sibilino/yii2/openlayers' => '@vendor/sibilino/yii2/openlayers/widget', 
			],
		]);
	}
	
	/* @var $widget OpenLayers */
	public function testInit()
	{
		$widget = OpenLayers::begin();
		$this->assertTrue(isset($widget->options['id']));
		$this->assertArrayHasKey('sibilino\yii2\openlayers\OpenLayersBundle', $widget->view->assetBundles);
	}
	
	public function testRun()
	{
		$this->expectOutputString('<div id="test" class="map"></div>');
		$widget = OpenLayers::begin([
			'id' => 'test',
			'scriptPosition' => View::POS_LOAD,
			'options' => [
				'class' => 'map',
			],
		]);
		OpenLayers::end();
		$this->assertArrayHasKey(View::POS_LOAD, $widget->view->js);
	}
	
	public function testView()
	{
		$widget = OpenLayers::begin([
			'mapOptions' => [
				'view' => [
					'center' => new JsExpression('ol.proj.transform([37.41, 8.82], "EPSG:4326", "EPSG:3857")'),
					'zoom' => 4,
				],
			],
		]);
		OpenLayers::end();
		$script = $this->getLastScript($widget);
		$this->assertRegExp('/view"?: ?new ol.View\({[^\w]*center"?: ?ol.proj.transform\(\[37.41, 8.82\], "EPSG:4326", "EPSG:3857"[^\w]*zoom"?: ?4[^\w]*}\)/', $script);
	}
	
	public function testLayers()
	{
		$widget = OpenLayers::begin([
			'mapOptions' => [
				'layers' => [
					'Tile' => 'OSM',
				],
			],
		]);
		OpenLayers::end();
		$script = $this->getLastScript($widget);
		$this->assertRegExp('/layers"?: ?\[[^\w]*new ol.layer.Tile\({[^\w]*source"?: ?new ol.source.OSM\(\)[^\w]*\]/', $script);
	}
	
	/**
	 * @param DygraphsWidget $widget
	 * @return string
	 */
	private function getLastScript($widget) {
		$scripts = $widget->view->js[$widget->scriptPosition];
		return end($scripts);
	}
}