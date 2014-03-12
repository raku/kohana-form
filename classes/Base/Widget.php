<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 19.12.13
 * Time: 17:09
 */

abstract class Base_Widget
{

    /**
     * @var array
     */
    protected $_attributes = array();

    /**
     * @var string
     */
    protected $_view_name = "";

    /**
     * @param $type
     * @param $attributes
     * @return mixed
     */
    public static function factory($type, $attributes)
    {
        $class = "Widget_" . $type;

        return new $class($attributes);
    }

    /**
     * @param $attributes
     */
    public function __construct($attributes)
    {
        $this->_attributes = $attributes;
    }

    /**
     * @return string
     */
    public function render()
    {
        return View::factory($this->_attributes["theme"] . "/" . $this->_view_name, $this->_attributes)
            ->render();
    }
} 