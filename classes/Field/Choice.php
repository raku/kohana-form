<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Choice extends Base_Field
{
    protected $_widget = "Select";

    protected $_rules = array(
        "not_empty" => NULL
    );

    public function __construct($options)
    {
        if (!isset($options["choices"]))
            throw new Kohana_Exception("You should define a list of a choices into a factory for Choice field");

        $this->_options = Arr::merge($this->_options, $options);
    }

    public function widget($type = NULL)
    {
        if ($type === NULL)
            return Widget::factory($this->_widget, array(
                "value" => $this->value(),
                "name" => $this->name(),
                "css_classes" => $this->css_class(),
                "formset_index" => $this->formset_index(),
                "theme" => $this->theme(),
                "choices" => $this->_options["choices"]
            ));

        $this->_widget = $type;

        return $this;
    }
} 