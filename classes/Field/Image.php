<?php
defined('SYSPATH') or die('No direct script access.');

class Field_Image extends Field_File
{
    protected $_rules = array(
        "Upload::valid" => NULL,
        "Upload::type" => array(':files', array('jpg', 'png', 'gif'))
    );

    protected function _process_file()
    {

        $image = $_FILES[$this->name()];

        if ($file = Upload::save($image, NULL, DOCROOT . $this->_upload_dir)) {
            $filename = strtolower(Text::random('alnum', 20)) . '.jpg';

            Image::factory($file)
                ->save(DOCROOT . $this->_upload_dir . $filename);

            // Delete the temporary file
            unlink($file);

            $this->_uploaded = true;

            $this->_file_address = $this->_upload_dir . $filename;

            return true;
        }

        return false;
    }

} 