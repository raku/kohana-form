<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Datetime extends Base_Field
{
    protected $_widget = "DateTime";

    protected $_rules = array(
        "not_empty" => NULL,
        "date"
    );
} 