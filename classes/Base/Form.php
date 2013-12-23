<?php defined('SYSPATH') or die('No direct script access.');


abstract class Base_Form implements Iterator
{

    private $__elements = array();

    private $__position = 0;

    public static function factory($classname)
    {
        $class = "Form_" . $classname;

        return new $class();
    }

    public function __construct()
    {
        $klass = get_called_class();
        foreach ($klass::meta() as $name => $field) {
            $this->__elements[] = $field->name($name);
        }
    }

    public function name()
    {
        return strtolower(str_replace("Form_", "", get_called_class()));
    }

    public function render()
    {
        $result = "";

        foreach ($this as $field) {
            $result .= $field;
        }

        return $result;
    }

    public function __toString()
    {
        return $this->render();
    }

    public static function meta()
    {
        return array();
    }

    public function current()
    {
        return $this->__elements[$this->__position];
    }

    public function next()
    {
        ++$this->__position;
    }

    public function key()
    {
        return $this->__position;
    }

    public function valid()
    {
        return isset($this->__elements[$this->__position]);
    }

    public function rewind()
    {
        $this->__position = 0;
    }
}