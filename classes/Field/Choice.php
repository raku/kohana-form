<?php
defined('SYSPATH') or die('No direct script access.');

class Choice extends Field
{
    protected $_widget = "Select";

    protected $_rules = array(
        "not_empty" => NULL
    );

    public function widget($type = NULL)
    {
        if (!isset($this->_options["choices"]))
            throw new Kohana_Exception("You should define a list of a choices into a factory for Choice field");

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