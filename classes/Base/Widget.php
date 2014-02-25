<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 19.12.13
 * Time: 17:09
 */

abstract class Base_Widget
{

    protected $_attributes = array();

    protected $_view_name = "";

    public static function factory($type, $attributes)
    {
        $class = "Widget_" . $type;

        return new $class($attributes);
    }

    public function __construct($attributes)
    {
        $this->_attributes = $attributes;
    }

    public function render()
    {
        return View::factory($this->_view_name, $this->_attributes)
            ->render();
    }
} 