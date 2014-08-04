<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Bool extends Base_Field
{
    protected $_widget = "Checkbox";

    protected $_rules = array();

    public function value($string = NULL)
    {
        if ($string === NULL)
            return (bool)$this->_value;

        $this->_value = (bool)$string;

        return $this;
    }
} 