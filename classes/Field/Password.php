<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Password extends Base_Field
{
    protected $_widget = "Password";

    protected $_rules = array(
        "not_empty" => NULL
    );
}