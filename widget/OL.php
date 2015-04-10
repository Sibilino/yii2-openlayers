<?php
namespace sibilino\yii2\openlayers;

use yii\web\JsExpression;
use yii\helpers\Json;

class OL extends JsExpression
{
	protected $class;
	protected $properties;
	
	public function __construct($class, array $properties = null)
	{
		$this->class = $class;
		$this->properties = $properties;
		$this->expression = $this->generateExpression();
	}
	
	protected function generateExpression()
	{
		if (isset($this->properties))
			$properties = Json::encode($this->properties);
		else
			$properties = '';
		return "new ol.$this->class($properties)";
	}
	
}