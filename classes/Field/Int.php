<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Int extends Base_Field
{
    protected $_widget = "TextInput";

    protected $_rules = array(
        "not_empty" => NULL,
        "numeric" => NULL,
        "digit" => NULL
    );
} 