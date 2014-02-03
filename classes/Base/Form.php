<?php defined('SYSPATH') or die('No direct script access.');


abstract class Base_Form implements Iterator
{

    protected $__elements = array();

    private $__position = 0;

    public static function factory($classname, $data = NULL, $id = NULL)
    {
        $class = "Form_" . $classname;

        return new $class($data, $id);
    }

    public function __construct($data = NULL, $id = NULL)
    {
        $klass = get_called_class();

        $meta = $klass::meta();

        foreach ($meta as $name => $field)
        {

            if ($data !== NULL)
            {
                if (isset($data[$name]))
                {
                    $field->value($data[$name]);
                }
            }

            $this->__elements[] = $field->name($name);
        }
    }

    public function validate()
    {

        foreach ($this->__elements as $element)
        {
            if (!$element->valid())
            {
                $this->__errors[$element->name()] = $element->errors();
            }
        }

        return empty($this->__errors);
    }

    public function errors()
    {
        return $this->__errors;
    }

    public function name()
    {
        return strtolower(str_replace("Form_", "", get_called_class()));
    }

    public function render()
    {
        $result = "";

        foreach ($this as $field)
        {
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