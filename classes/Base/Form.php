<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Class Base_Form
 *
 * @package    Kohana/kohana-form
 * @author     Latyshenko Roman
 * @version    0.0.0.2
 *
 *
 */
abstract class Base_Form extends Kohana_Form implements Iterator
{

    /**
     * Storing form fields
     * @var array
     */
    private
        $__elements = array();

    /**
     * @var int
     */
    private
        $__position = 0;
    /**
     * Storing validation errors
     *
     * @var array
     */
    private
        $__errors = array();

    /**
     * Flag which says, should we add formset index to field name
     *
     * @var bool
     */
    private
        $__is_formset_element = false;

    /**
     * @var int
     */
    private
        $__number = 0;

    /**
     * @var array
     */
    protected
        $_options = array(
        "valid_messages_file" => "",
        "theme" => "base"
    );

    /**
     * @param $classname
     * @param null $data
     * @param null $id
     * @return mixed
     */
    public
    static function factory($classname, $data = NULL, $id = NULL)
    {
        $class = "Form_" . $classname;

        return new $class($data, $id);
    }

    /**
     * Return form fields
     * @return array
     */
    public function elements()
    {
        return $this->__elements;
    }

    /**
     * @param null $data
     * @param null $id
     */
    public function __construct($data = NULL, $id = NULL)
    {
        $klass = get_called_class();

        $meta = $klass::meta();

        $this->_options =
            Arr::merge($this->_options, Arr::get($meta, "options"));

        foreach (Arr::get($meta, "fields") as $name => $field) {

            if ($data !== NULL) {
                if (isset($data[$name])) {
                    $field->value($data[$name]);
                } else {
                    $field->value(NULL);
                }
            }

            $field->theme(Arr::get($this->_options, "theme"));

            $this->add_field($field->name($name));
        }
    }

    /**
     *
     * Says is this an formset form
     *
     * @param null $value
     * @return bool
     */
    public function is_formset_element($value = NULL)
    {
        if ($value === NULL)
            return $this->__is_formset_element;

        $this->__is_formset_element = $value;

        return $this;
    }


    /**
     * Number in formset array
     *
     * @param null $value
     * @return int
     */
    public function number($value = NULL)
    {
        if ($value === NULL)
            return $this->__number;

        $this->__number = $value;

        return $this;
    }

    /**
     * Check all fields and fill the errors if needed
     *
     * @return bool
     */
    public function validate()
    {

        foreach ($this->__elements as $element) {
            if (!$element->valid(Arr::get($this->_options, "valid_messages_file"))) {
                $this->__errors = Arr::merge($this->__errors, $element->errors());
            }
        }

        return empty($this->__errors);
    }

    /**
     * Return validation errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->__errors;
    }

    /**
     *
     * Form name
     * @return string
     */
    public function name()
    {
        return strtolower(str_replace("Form_", "", get_called_class()));
    }

    /**
     * Adding field to form
     *
     * @param Base_Field $field
     * @return $this
     */
    public function add_field(Base_Field $field)
    {
        $this->__elements[] = $field;

        return $this;
    }

    public function field($key)
    {
        foreach ($this->elements() as $field) {
            if ($field->name() == $key)
                return $field->name();
            else
                return NULL;
        }
    }

    /**
     * Show form on a page
     *
     * @return string
     */
    public function render()
    {
        $result = "";

        foreach ($this as $field) {
            $result .= $field;
        }

        return $result;
    }

    /**
     * Some magic
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

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
     * @return mixed
     */
    public function current()
    {
        if ($this->is_formset_element()) {
            $this->__elements[$this->__position]->formset_index("[" . $this->number() . "][]");
        }
        return $this->__elements[$this->__position];
    }

    /**
     *
     */
    public function next()
    {
        ++$this->__position;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->__position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->__elements[$this->__position]);
    }

    /**
     *
     */
    public function rewind()
    {
        $this->__position = 0;
    }
}
