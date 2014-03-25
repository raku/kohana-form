<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Hidden extends Base_Field
{
    protected $_widget = "Hidden";

    protected $_rules = array(
        "not_empty" => array(":value")
    );
} 