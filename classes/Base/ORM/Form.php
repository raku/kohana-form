<?php defined('SYSPATH') or die('No direct script access.');


class Base_ORM_Form extends Base_Form
{
    private $__instance = NULL;
    private $__meta = array(
        "model" => NULL,
        "display_fields" => array()
    );

    public function __construct($data = array(), $id = NULL)
    {
        $klass = get_called_class();

        $this->__meta = Arr::merge($this->__meta, $klass::meta());

        $columns = $this->__meta["model"]->list_columns();

        if ($id !== NULL)
        {
            $this->__instance = $this->__meta["model"]->where($this->__meta["model"]->primary_key(), "=", $id)->find();
            $iterated = array();

            foreach ($this->__instance->as_array() as $key => $value)
            {
                $iterated[$key] = $value;
            }
            $data = Arr::merge($iterated, $data);
        }

        foreach ($columns as $column)
        {
            $name = $column["column_name"];

            if (in_array($name, $this->__meta["display_fields"]) || empty($this->__meta["display_fields"]))
            {
                $this->__elements[] = Field::factory($this->__transform_value($column["data_type"]))
                    ->name($column["column_name"])
                    ->value(isset($data[$name]) ? $data[$name] : "");
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
            $this->__instance = $this->__meta["model"];

        foreach ($this->__elements as $element)
        {
            $this->__instance->{$element->name()} = $element->value();
        }

        $this->__instance->save();
    }
}