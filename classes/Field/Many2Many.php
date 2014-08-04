<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Many2Many extends Field_One2Many
{
    public function __construct($options)
    {
        if (!isset($options["model"]))
            throw new Kohana_Exception("You should define a model into a factory for Many2Many field");

        $list = $options["model"]->find_all();

        $choices = array();

        foreach ($list as $instance) {
            $choices[$instance->pk()] = (string)$instance;
        }

        $options["choices"] = $choices;

        $this->_options = Arr::merge($this->_options, $options);
    }
} 