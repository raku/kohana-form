<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Class Base_ORM_Form
 *
 *
 * @package    Kohana/kohana-form
 * @author     Latyshenko Roman
 * @version    0.0.0.2
 *
 *
 */
class Base_ORM_Form extends Base_Form
{
    /**
     * Storing an ORM instance
     * @var null
     */
    private $__instance = NULL;
    /**
     * Some options for customizing.
     *
     * "model" => ORM instance of a model for creating form
     * "display_fields" => What fields you want to show
     * "except_fields" => What fields you want to hide
     * "valid_messages_file" => Name of a file with beauty valid messages
     * "theme" => I think you could get it by yourself
     *
     * @var array
     * @access protected
     */
    protected $_options = array(
        "model" => NULL,
        "display_fields" => array(),
        "except_fields" => array(),
        "valid_messages_file" => "",
        "theme" => "base"
    );

    /**
     * Storing a form fields
     *
     * @var array|mixed
     */
    private $__fields = array();

    /**
     * @param array $data
     * @param null $id
     */
    public function __construct($data = array(), $id = NULL)
    {

        $klass = get_called_class();

        $meta = $klass::meta();

        $this->_options = Arr::merge($this->_options, Arr::get($meta, "options"));

        $this->__fields = Arr::get($meta, "fields");

        if ($data instanceof ORM)
            $data = $data->as_array();

        $columns = Arr::get($this->_options, "model")->list_columns();

        if ($id !== NULL) {
            $this->__instance = Arr::get($this->_options, "model")
                ->where(Arr::get($this->_options, "model")->primary_key(), "=", $id)
                ->find();
            $iterated = array();

            foreach ($this->__instance->as_array() as $key => $value) {
                $iterated[$key] = $value;
            }
            $data = Arr::merge($iterated, $data);
        }

        foreach ($columns as $column) {
            $name = $column["column_name"];

            $field = $this->__create_field($name, $column, $data);

            $field->theme(Arr::get($this->_options, "theme"));

            if (
                in_array($name, Arr::get($this->_options, "display_fields")) ||
                empty($this->_options["display_fields"])
            ) {
                if (!in_array($name, Arr::get($this->_options, "except_fields")))
                    $this->add_field($field);
            }
        }
    }

    /**
     * @param $name
     * @param $column
     * @param $data
     * @return mixed
     */
    private function __create_field($name, $column, $data)
    {
        return Arr::get($this->__fields, $name, false) ?
            Arr::get($this->__fields, $name)
                ->name($name)
                ->value(isset($data[$name]) ? $data[$name] : "")
            :
            Field::factory($this->__transform_value($column["data_type"]))
                ->name($name)
                ->value(isset($data[$name]) ? $data[$name] : "");
    }

    /**
     * @param $value
     * @return string
     */
    private function __transform_value($value)
    {
        return Inflector::underscore(
            ucwords(
                $value
            ));
    }

    /**
     *Saving an instance with data in form
     */
    public function save()
    {
        if ($this->__instance === NULL)
            $this->__instance = Arr::get($this->_options, "model");

        foreach ($this->elements() as $element) {
            $this->__instance->{$element->name()} = $element->value();
        }

        $this->__instance->save();
    }


}