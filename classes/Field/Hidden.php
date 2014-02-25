<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 19.02.14
 * Time: 14:42
 */

class Field_Hidden extends Base_Field
{
    protected $_widget = "Hidden";

    protected $_rules = array(
        "not_empty" => array(":value")
    );
} 