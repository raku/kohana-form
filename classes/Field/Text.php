<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Text extends Base_Field
{
    protected $_widget = "Textarea";

    protected $_rules = array(
        "not_empty" => NULL
    );
} 