<?php
namespace sibilino\yii2\openlayers;

use Yii;
use yiiunit\TestCase;
use yii\web\JsExpression;

class OpenLayersTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->mockApplication([
			'vendorPath' => __DIR__.'/../../..',
			'components' => [
				'assetManager' => [
					'basePath' => __DIR__.'/../../../../assets',
					'baseUrl' => 'http://localhost/basic/assets',
				],
			],
			'aliases' => [
				'@sibilino/yii2/openlayers' => __DIR__.'/../widget', 
			],
		]);
	}
	
	/* @var $widget OpenLayers */
	public function testInit()
	{
		$widget = OpenLayers::begin();
		$this->assertTrue(isset($widget->options['id']));
		$this->assertTrue(isset($widget->mapOptions['target']));
		$this->assertArrayHasKey(OpenLayersBundle::className(), $widget->view->assetBundles);
		$this->assertArrayHasKey(OLModuleBundle::className(), $widget->view->assetBundles);
	}
	
	public function testRun()
	{
		$this->expectOutputString('<div id="test" class="map"></div>');
		$widget = OpenLayers::begin([
			'id' => 'test',
            'mapOptionScript' => [
                '@vendor/js/test1.js',
                '@vendor/js/test2.js',
            ],
			'options' => [
				'class' => 'map',
			],
		]);
		OpenLayers::end();
        $this->assertArrayHasKey(Yii::getAlias('@vendor/js/test1.js'), $widget->view->assetBundles);
        $this->assertArrayHasKey(Yii::getAlias('@vendor/js/test2.js'), $widget->view->assetBundles);
		$this->assertContains('olwidget.createMap(', $this->getLastScript($widget));
	}
	
	/**
	 * @dataProvider optionProvider
	 */
	public function testOptions($options, $outputRegExp)
	{
		$widget = OpenLayers::begin($options);
		OpenLayers::end();
		$this->assertRegExp("/$outputRegExp/", $this->getLastScript($widget));
	}
	
	public function optionProvider()
	{
		return [
			[ // Simplified View
				[
					'mapOptions' => [
						'view' => [
							'center' => new JsExpression('ol.proj.transform([37.41, 8.82], "EPSG:4326", "EPSG:3857")'),
							'zoom' => 4,
						],
					],
				],
				'view"?: ?new ol.View\({[^\w]*center"?: ?ol.proj.transform\(\[37.41, 8.82\], "EPSG:4326", "EPSG:3857"[^\w]*zoom"?: ?4[^\w]*}\)'
			],
			[ // Custom View
				[
					'mapOptions' => [
						'view' => new OL('View', ['center'=>[0, 0], 'zoom'=>2]),
					],
				],
				'view"?: ?new ol.View\({[^\w]*center"?: ?\[0, ?0\][^\w]*zoom"?: ?2[^\w]*}\)'
			],
			[ // Simplified Layers
				[
					'mapOptions' => [
						'layers' => [
							'Tile' => 'OSM',
						],
					],
				],
				'layers"?: ?\[[^\w]*new ol.layer.Tile\({[^\w]*source"?: ?new ol.source.OSM\(\)[^\w]*\]'
			],
			[
				[
					'mapOptions' => [
						'layers' => [
							'Tile' => [
								'visible' => false,
							],
						],
					],
				],
				'layers"?: ?\[[^\w]*new ol.layer.Tile\({[^\w]*visible"?: ?false[^\w]*\]'
			],
			[
				[
					'mapOptions' => [
						'layers' => [
							'Tile' => [
								'source' => new OL('source.MapQuest', [
									'layer' => 'sat',
								])
							],
						],
					],
				],
				'layers"?: ?\[[^\w]*new ol.layer.Tile\({[^\w]*source"?: ?new ol.source.MapQuest\([^\w]*layer"?: ?"sat"[^\w]\)[^\w]*\]'
			],
			[ // Custom Layers
				[
					'mapOptions' => [
						'layers' => [
							new OL('layer.Tile', ['source'=>'osmsource']),
						],
					],
				],
				'layers"?: ?\[[^\w]*new ol.layer.Tile\({[^\w]*source"?: ?"osmsource"[^\w]*\]'
			],
		];
	}
		
	/**
	 * @param OpenLayers $widget
	 * @return string
	 */
	private function getLastScript($widget) {
		$scripts = end($widget->view->js);
		return end($scripts);
	}
}