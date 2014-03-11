<?php
defined('SYSPATH') or die('No direct script access.');

/**
 * Class Base_Field
 */
abstract class Base_Field
{

    /**
     * @var string
     */
    protected $_widget = "";

    /**
     * @var string
     */
    protected $_value = "";

    /**
     * @var string
     */
    protected $_name = "";

    /**
     * @var array
     */
    protected $_errors = array();

    /**
     * @var array
     */
    protected $_css_classes = array();

    /**
     * @var array
     */
    protected $_rules = array();

    /**
     * @var array
     */
    protected $_options = array();

    private $__formset_index = "";

    /**
     * @param $type
     * @param array $options
     * @return mixed
     */
    public static function factory($type, $options = array())
    {
        $class = "Field_" . $type;

        return new $class($options);
    }

    /**
     * @param $options
     */
    public function __construct($options)
    {
        $this->_options = Arr::merge($this->_options, $options);
    }

    /**
     * @param null $string
     * @return $this|string
     */
    public function value($string = NULL)
    {
        if ($string === NULL)
            return $this->_value;

        $this->_value = $string;

        return $this;
    }

    /**
     * @param null $string
     * @return $this|string
     */
    public function name($string = NULL)
    {
        if ($string === NULL)
            return $this->_name;

        $this->_name = $string;

        return $this;
    }

    /**
     * @param null $type
     * @return $this
     */
    public function widget($type = NULL)
    {
        if ($type === NULL)
            return Widget::factory($this->_widget, array(
                "value" => $this->value(),
                "name" => $this->name(),
                "css_classes" => $this->css_class(),
                "formset_index" => $this->formset_index()
            ));

        $this->_widget = $type;

        return $this;
    }

    /**
     * @param string $file
     * @return bool
     */
    public function valid($file = "")
    {
        $validation = Validation::factory(array(
            $this->name() => $this->value()
        ));

        foreach ($this->_rules as $rule => $args) {
            $validation->rule($this->name(), $rule, $args);
        }

        if ($validation->check()) {
            return true;
        } else {
            $this->errors($validation->errors($file));
            return false;
        }
    }

    /**
     * @param null $class
     * @return $this|array
     */
    public function css_class($class = NULL)
    {
        if ($class === NULL)
            return $this->_css_classes;

        if (is_array($class))
            $this->_css_classes = Arr::merge($this->_css_classes, $class);

        if (is_string($class))
            $this->_css_classes[] = $class;

        return $this;
    }

    /**
     * @param array $errors
     * @return $this|array
     */
    public function errors($errors = array())
    {
        if (empty($errors))
            return $this->_errors;

        $this->_errors = Arr::merge($this->_errors, $errors);

        return $this;
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $widget = $this->widget();

        return $widget->render();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->render();
    }

    public function formset_index($string = NULL)
    {
        if ($string === NULL)
            return $this->__formset_index;

        $this->__formset_index = $string;

        return $this;
    }

}