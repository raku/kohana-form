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
     * @var array|mixed
     */
    private $__2m_fields = array();

    /**
     * @var string
     */
    protected $_relation_postfix = "_id";

    /**
     * @var array
     */
    private $__relations = array();

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
        $this->__2m_fields = Arr::get($meta, "2m_fields", array());

        if ($this->__instance === NULL)
            $this->__instance = Arr::get($this->_options, "model");

        $this->__relations = $this->__load_relations();


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

        $this->__load_2m($data);

    }

    /**
     * @param null $value
     * @return $this|array
     */
    public function relations($value = NULL)
    {
        if ($value === NULL)
            return $this->__relations;

        $this->__relations = $value;

        return $this;
    }

    /**
     * @param $name
     * @param $column
     * @param $data
     * @return mixed
     */
    private function __create_field($name, $column, $data)
    {
        $unified_name = str_replace($this->_relation_postfix, "", $column["column_name"]);

        return Arr::get($this->__fields, $unified_name, false) ?
            Arr::get($this->__fields, $unified_name)
                ->name($unified_name)
                ->value(isset($data[$unified_name]) ? $data[$unified_name] : (isset($data[$name]) ? $data[$name] : ""))
            :
            Field::factory($this->__transform_value($column["data_type"]))
                ->name($unified_name)
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
     * @param $key
     * @return mixed
     */
    private function __relation_type($key)
    {
        return Arr::get($this->__relations, $key, NULL);
    }

    /**
     * @return array
     */
    private function  __load_relations()
    {
        $belongs_to = $this->__instance->belongs_to();
        $has_many = $this->__instance->has_many();
        $has_one = $this->__instance->has_one();

        $result = array();

        foreach (array("belongs_to", "has_many", "has_one") as $type) {
            foreach ($$type as $key => $value) {
                $result[$key] = $type;
            }
        }

        return $result;
    }

    /**
     *Saving an instance with data in form
     */
    public function save()
    {
        foreach ($this->elements() as $element) {

            $type = $this->__relation_type($element->name());


            switch ($type) {
                case NULL:
                    $this->__instance->{$element->name()} = $element->value();
                    break;
                case "belongs_to":
                    $this->__write_belongs_to($element);
                    break;
            }

        }

        try {
            $this->__instance->save();
        } catch (Exception $e) {

        }

        $this->_save_2m();
    }

    /**
     * @param Field_BelongsTo $element
     * @return $this
     */
    private function __write_belongs_to(Field_BelongsTo $element)
    {
        $this->__instance->{$element->name()} = $element->model()
            ->where($element->model()->primary_key(), "=", $element->value())->find();

        return $this;
    }

    /**
     *
     */
    private function __load_2m($data = array())
    {
        foreach ($this->__2m_fields as $name => $field) {
            $field->theme(Arr::get($this->_options, "theme"));
            $field->name($name);

            foreach ($this->__instance->{$name}->find_all() as $object) {
                $data["loaded"][$name][] = $object->pk();
            }

            if (isset($data["loaded"][$name])) {
                $field->value($data["loaded"][$name]);
            }

            if (isset($data[$name]))
                $field->value($data[$name]);

            $this->add_field($field);
        }
    }

    /**
     *
     */
    protected function _save_2m()
    {
        foreach ($this->elements() as $element) {

            $type = $this->__relation_type($element->name());

            $many_to_many = !is_null(Arr::get(Arr::get($this->__instance->has_many(), $element->name()), "through"));


            switch ($type) {
                case NULL:
                    break;
                case "has_many":
                    if (!$many_to_many)
                        $this->__write_has_many($element);
                    else
                        $this->__write_many_2_many($element);
                    break;
            }

        }
    }

    /**
     * @param $element
     * @return $this
     */
    private function __write_has_many($element)
    {
        foreach ($element->value() as $id) {
            foreach ($element
                         ->model()->clear()
                         ->where($element->model()->primary_key(), "=", $id) as $object) {
                $object->{$this->name()} = NULL;
                $object->save();
            }
            $element
                ->model()->clear()
                ->where($element->model()->primary_key(), "=", $id)
                ->find()
                ->{$this->name()} = $this->__instance;
            $element->model()->save();
        }

        return $this;
    }

    private function __write_many_2_many($element)
    {
        foreach ($this->__instance->{$element->name()}->find_all() as $object) {
            $this->__instance->remove($element->name(), $object);
        }
        foreach ($element->value() as $id) {
            $this->__instance->add($element->name(), $element->model()->clear()
                ->where($element->model()->primary_key(), "=", $id)->find());
        }

        return $this;
    }
}