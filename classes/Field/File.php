<?php
defined('SYSPATH') or die('No direct script access.');

class Field_File extends Base_Field
{

    private $__uploaded = false;

    private $__file_address = NULL;

    protected $_widget = "Upload";

    protected $_upload_dir = "";

    protected $_rules = array(
        "Upload::valid" => array(":value"),
    );

    public function __construct($options)
    {
        parent::__construct($options);

        $this->_upload_dir = Kohana::$config->load("form.upload_dir");
    }

    public function value($string = NULL)
    {
        if ($string === NULL) {
            if (!$this->__uploaded && !empty($_FILES))
                $this->__process_file();
            return $this->__file_address;
        }

        $this->__file_address = $string;

        return $this;
    }

    public function valid($file = "")
    {
        $validation = Validation::factory(array(
            $this->name() => $_FILES[$this->name()]
        ));

        $validation->bind(":files", $_FILES[$this->name()]);

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

    private function __process_file()
    {

        $file = $_FILES[$this->name()];

        if ($file = Upload::save($file, NULL, DOCROOT . $this->_upload_dir)) {

            $this->__uploaded = true;

            $this->__file_address = $this->_upload_dir . $file;

            return true;
        }

        return false;
    }

} 