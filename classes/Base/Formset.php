<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 06.03.14
 * Time: 14:06
 */

class Base_Formset implements Iterator
{


    private $__forms = array();

    private $__options = array(
        "base_form" => NULL,
        "count" => 3,
        "theme" => "formset",
        "template" => "template"
    );

    public static function factory($class)
    {
        $classname = "Formset_" . $class;

        return new $classname();
    }

    public function __construct()
    {
        $klass = get_called_class();

        $this->__options = Arr::merge($this->__options, $klass::meta());

        if (!Arr::get($this->__options, "base_form", false)) {
            throw new Kohana_Exception("Please, define a base form for formset");
        } else {
            $this->_build_formset();
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function __toString()
    {
        return $this->render();
    }

    private function _build_formset()
    {
        $base_form_name = Arr::get($this->__options, "base_form");

        for ($i = 0; $i < Arr::get($this->__options, "count", 1); $i++) {
            $this->__forms[] = Form::factory($base_form_name);
        }
    }

    public function render()
    {
        $view_name = Arr::get($this->__options, "template");
        $theme_name = Arr::get($this->__options, "theme");

        $view = View::factory("$theme_name/$view_name", array(
            "forms" => $this->__forms
        ));

        return $view->render();
    }
}