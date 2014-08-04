<?php
defined('SYSPATH') OR die('No direct access allowed.');

class Field_URL extends Base_Field
{

    protected $_widget = "TextInput";

    protected $_rules = array(
        "not_empty" => NULL,
        "url" => NULL
    );
} 