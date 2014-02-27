<?php defined('SYSPATH') or die('No direct script access.');


class Base_ORM_Form extends Base_Form
{
    private $__instance = NULL;
    protected $_options = array(
        "model" => NULL,
        "display_fields" => array(),
        "valid_messages_file" => ""
    );

    private $__fields = array();

    public function __construct($data = array(), $id = NULL)
    {
        $klass = get_called_class();

        $meta = $klass::meta();

        $this->_options = Arr::merge($this->_options, Arr::get($meta, "options"));

        $this->__fields = Arr::get($meta, "fields");

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

            $field = Arr::get($this->__fields, $name, false) ?
                Arr::get($this->__fields, $name)
                    ->name($name)
                    ->value(isset($data[$name]) ? $data[$name] : "")
                :
                Field::factory($this->__transform_value($column["data_type"]))
                    ->name($name)
                    ->value(isset($data[$name]) ? $data[$name] : "");

            if (in_array($name, Arr::get($this->_options, "display_fields")) || empty($this->_options["display_fields"])) {
                $this->add_field($field);
            }
        }
    }

    private function __transform_value($value)
    {
        return Inflector::underscore(
            ucwords(
                $value
            ));
    }

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