<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Email extends Base_Field
{
    protected $_widget = "Email";

    protected $_rules = array(
        "not_empty" => array(":value"),
        "email" => array()
    );
}