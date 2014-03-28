<?php
defined('SYSPATH') or die('No direct script access.');

class Field_BelongsTo extends Base_Field
{
    protected $_widget = "Select";

    public function __construct($options)
    {
        if (!isset($options["model"]))
            throw new Kohana_Exception("You should define a model into a factory for BelongsTo field");

        $list = $options["model"]->find_all();

        $choices = array();

        foreach ($list as $instance) {
            $choices[$instance->pk()] = (string)$instance;
        }

        $options["choices"] = $choices;

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