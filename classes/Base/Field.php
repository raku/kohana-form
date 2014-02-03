<?php
defined('SYSPATH') or die('No direct script access.');

abstract class Base_Field
{

    protected $_widget = "";

    protected $_value = "";

    protected $_name = "";

    protected $_rules = array();

    protected $_options = array();

    public static function factory($type, $options = array())
    {
        $class = "Field_" . $type;

        return new $class($options);
    }

    public function __construct($options)
    {
        $this->_options = Arr::merge($this->_options, $options);
    }

    public function value($string = NULL)
    {
        if ($string === NULL)
            return $this->_value;

        $this->_value = $string;

        return $this;
    }

    public function name($string = NULL)
    {
        if ($string === NULL)
            return $this->_name;

        $this->_name = $string;

        return $this;
    }

    public function widget($type = NULL)
    {
        if ($type === NULL)
            return Widget::factory($this->_widget, array(
                "value" => $this->value(),
                "name" => $this->name()
            ));

        $this->_widget = $type;

        return $this;
    }

    public function valid() // ещё не доделано
    {
        $validation = Validation::factory(array(
            $this->name() => $this->value()
        ));


    }

    public function render()
    {
        $widget = $this->widget();

        return $widget->render();
    }

    public function __toString()
    {
        return $this->render();
    }

}