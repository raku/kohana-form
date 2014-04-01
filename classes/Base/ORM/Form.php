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

    protected $_relation_postfix = "_id";

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

    private function __relation_type($key)
    {
        return Arr::get($this->__relations, $key, NULL);
    }

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

        $this->__instance->save();
    }

    private function __write_belongs_to(Field_BelongsTo $element)
    {
        $this->__instance->{$element->name()} = $element->model()
            ->where($element->model()->primary_key(), "=", $element->value())->find();


        return $this;
    }
}