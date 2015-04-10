<?php
namespace sibilino\yii2\openlayers;

use yiiunit\TestCase;
use yii\web\View;

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
}