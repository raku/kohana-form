Как пользоваться:
===

Создание формы:



```
<?php
defined('SYSPATH') OR die('No direct access allowed.');

class Form_Login extends Form
{
    public static function meta()
    {
        return array(
            "fields" => array(
                "login" => Field::factory("Varchar"),
                "password" => Field::factory("Password"),
            ),

            "options" => array( // заполняется по желанию. может быть пустым массивом.
                "valid_messages_file" => "login", // файл в котором лежат сообщения валидации, должен находиться в папке messages
                "theme" => "base" // тема формы. base - тема по умолчанию, из коробки есть 2 темы: base, nolabels. nolabels это таже тема base только без тегов label
            ),
        );
    }
} 

```

Имеющиеся типы полей:

<ul>
	<li> Email</li>
<li> Hidden </li>
<li> Image</li>
<li> Int </li>
<li> Password </li>
<li> Text </li>
<li> Timestamp</li>
<li> Varchar </li>
<li> Int Unsigned </li>
</ul>

Как показать форму:

Просто показать

```
 <form>
     <?php echo $form; ?>
     <input type="submit" value="Add"/>
 </form>

```

Показать с стилями bootstrap:

```
       <form method="POST" role="form">
            <?php foreach ($form as $field): ?>
                <div class="form-group">
                    <?php $field->css_class(array("form-control")); ?>
                    <?php foreach ($field->errors() as $error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php echo $field; ?>
                </div>
            <? endforeach; ?>
            <input type="submit" class="btn btn-primary" value="Add"/>
        </form>

```

Создать модельную форму:

```
class Form_Article extends ModelForm
{

    public static function meta()
    {
        return array(
            "fields" => array( //необязательно для заполнения. используется для переопределения полей, если это требуется.
                "image" => Field::factory("Image")
            ),
            "options" => array(
                "model" => ORM::factory("Article"), //собственно модель из которой будем генерить форму
                "display_fields" => array("title", "body", "image"), //отображаемые поля
                "valid_messages_file" => "news", //используемый файл для сообщений валидации
                "except_fields" => array() //какие поля скрыть и не показывать
            ),
        );
    }
}

```

Создать форму для определенной сущности в базе данных


```
Form::factory("Article", array(), $id);

```

Создать форму для определенного набора данных


```
Form::factory("Article", array("title" => "Hello!"));

```

Получить и сохранить модельную форму:

```
public function action_add()
    {
       if ($this->request->method() == "POST") {

            $form = Form::factory("Article", $this->request->post());

            $form->add_field(
                Field::factory("Hidden")
                    ->name("user")
                    ->value(Auth::instance()
                        ->get_user())
            );
            if ($form->validate())
                $form->save();
        }       
    }

```

Создать formset

```
<?php
defined('SYSPATH') OR die('No direct access allowed.');

class Formset_News extends Formset
{

    public static function meta()
    {
        return array(
            "base_form" => "News",
            "theme" => "bootstrap"
        );
    }
} 

```

Формсеты так же имплементируют Iterator, поэтому можно легко получить доступ к каждому элементу.
