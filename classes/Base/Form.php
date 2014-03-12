<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Class Base_Form
 */
abstract class Base_Form implements Iterator
{

    /**
     * @var array
     */
    private $__elements = array();

    /**
     * @var int
     */
    private $__position = 0;
    /**
     * @var array
     */
    private $__errors = array();

    /**
     * @var bool
     */
    private $__is_formset_element = false;

    /**
     * @var int
     */
    private $__number = 0;

    /**
     * @var array
     */
    protected $_options = array(
        "valid_messages_file" => "",
    );

    /**
     * @param $classname
     * @param null $data
     * @param null $id
     * @return mixed
     */
    public static function factory($classname, $data = NULL, $id = NULL)
    {
        $class = "Form_" . $classname;

        return new $class($data, $id);
    }

    /**
     * @return array
     */
    protected function elements()
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
                }
            }

            $this->add_field($field->name($name));
        }
    }

    /**
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
     * @return array
     */
    public function errors()
    {
        return $this->__errors;
    }

    /**
     * @return string
     */
    public function name()
    {
        return strtolower(str_replace("Form_", "", get_called_class()));
    }

    /**
     * @param Base_Field $field
     * @return $this
     */
    public function add_field(Base_Field $field)
    {
        $this->__elements[] = $field;

        return $this;
    }

    /**
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
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
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