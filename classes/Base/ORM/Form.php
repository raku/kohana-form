<?php defined('SYSPATH') or die('No direct script access.');


class Base_ORM_Form extends Base_Form
{
    public function __construct($data = NULL)
    {
        $klass = get_called_class();

        $meta = $klass::meta();

        $columns = $meta["model"]->list_columns();

        foreach ($columns as $column) {

            $name = $column["column_name"];

            if (in_array($name, $meta["display_fields"]))
                $this->__elements[] = Field::factory(
                    Inflector::underscore(
                        ucwords(
                            $column["data_type"]
                        )))
                    ->name($column["column_name"])
                    ->value(isset($data[$name]) ? $data[$name] : "");
        }
    }
}