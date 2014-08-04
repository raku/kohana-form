<?php
defined('SYSPATH') or die('No direct script access.');

class Field_MultiChoice extends Field_Choice
{
    protected $_widget = "MultiSelect";

    protected $_value = array();
}