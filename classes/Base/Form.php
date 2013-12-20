<?php defined('SYSPATH') or die('No direct script access.');


abstract class Base_Form implements Iterator
{

    private $__elements = array();

    public static function factory($classname)
    {
        $class = "Form_" . $classname;

        return new $class();
    }

    public function __construct()
    {

    }

    public function meta()
    {
        return array();
    }

    public function current()
    {
        // TODO: Implement current() method.
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function key()
    {
        // TODO: Implement key() method.
    }

    public function valid()
    {
        // TODO: Implement valid() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }
}