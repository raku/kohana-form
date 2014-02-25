Kohana-form
=========

Ok, guys, it's time to write some docs

Kohana-form it's a simple module for rendering forms, inspired by django forms module.


How it works
=========

* As first you should create form class.  
For example:


```
<?php defined('SYSPATH') or die('No direct script access.');


class Form_Contacts extends Form 
{
    public static function meta() // in this method you should return list of fields for the form
    {
        return array(
            "text" => Field::factory("Email"),
        );
    }
} 
```


* After this you can call method in your  
view:

```
<form action="/" name="<?php echo $form->name(); ?>" method="POST">
    <?php echo $form ?>
    <input type="submit"/>
</form>
```

And that's all.

#For ORM

You can create forms from simple ORM models.

Let's do some:

...Form/News.php

```
<?php
defined('SYSPATH') OR die('No direct access allowed.');

class Form_News extends ModelForm
{

    protected $_valid_messages_file = "news";

    public static function meta()
    {
        return array(
            "model" => ORM::factory("News"),
            "display_fields" => array("title", "body")
        );
    }
} 
```

...Controller/News.php

```
 public function action_add()
    {
        $form = Form::factory("News");

        if ($this->request->method() == "POST") {

            $form = Form::factory("News", $this->request->post());

            $form->add_field(
                Field::factory("Hidden")
                    ->name("user")
                    ->value(Auth::instance()
                        ->get_user())
            );
            if ($form->validate())
                $form->save();
        }

        $this->_data["form"] = $form;
    }
```

That's all. This module is totally alpha, because here is a lot of work to do yet. I need your help, for write docs, for finish all widgets and many many other important things. 

Thank you.

