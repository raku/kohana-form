<?php
defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Class Base_Formset
 *
 * Creating multiforms with clean formset indexes for managing repeated data
 *
 * @package    Kohana/kohana-form
 * @author     Latyshenko Roman
 * @version    0.0.0.2
 *
 */
class Base_Formset implements Iterator
{

    /**
     * @var int
     */
    private $__position = 0;

    /**
     * Storing created forms
     * @var array
     */
    private $__forms = array();

    /**
     * Some options for customizing
     *
     * "base_form" => String name of a base form
     * "count" => It's obvious, I think.
     *
     * @var array
     */
    private $__options = array(
        "base_form" => NULL,
        "count" => 3,
        "theme" => "formset",
        "template" => "template"
    );


    /**
     * Little feature for theming.
     *
     * If you want to show some unusual data in your theme, you should give it to method extra_data()
     *
     * @var array
     */
    private $__extra_data = array();

    /**
     * Basic method for initialization
     *
     * @return array
     */
    public static function meta()
    {
        return array();
    }

    /**
     * @param $class
     * @return mixed
     */
    public static function factory($class, $data = NULL)
    {
        $classname = "Formset_" . $class;

        return new $classname($data);
    }

    /**
     *
     */
    public function __construct($data = NULL)
    {
        $klass = get_called_class();

        $this->__options = Arr::merge($this->__options, $klass::meta());

        if ($data !== NULL)
            $this->__options["count"] += count($data);


        if (!Arr::get($this->__options, "base_form", false)) {
            throw new Kohana_Exception("Please, define a base form for formset");
        } else {
            $this->_build_formset($data);
        }
    }

    /**
     *
     */
    private function _build_formset($data = NULL)
    {
        $base_form_name = Arr::get($this->__options, "base_form");
        for ($i = 1; $i <= Arr::get($this->__options, "count", 1); $i++) {

            $this->__forms[] = Form::factory($base_form_name, isset($data[$i - 1]) ? $data[$i - 1] : NULL)
                ->is_formset_element(true)
                ->number($i);
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        $view_name = Arr::get($this->__options, "template");
        $theme_name = Arr::get($this->__options, "theme");

        $view = View::factory("$theme_name/$view_name", array(
            "forms" => $this->__forms,
            "extra_data" => $this->extra_data()
        ));

        return $view->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $this->__forms[$this->__position];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->__position;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->__position;
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
        return isset($this->__forms[$this->__position]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->__position = 0;
    }

    /**
     * Little feature for theming.
     *
     * If you want to show some unusual data in your theme, you should give it to method extra_data()
     *
     * @param null $data
     * @return $this|array
     */
    public function extra_data($data = NULL)
    {
        if ($data === NULL)
            return $this->__extra_data;

        $this->__extra_data = $data;

        return $this;
    }

}