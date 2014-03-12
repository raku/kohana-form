<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/Kalendae/0.4.1/kalendae.standalone.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Kalendae/0.4.1/kalendae.css"/>

<input type="text" class="auto-kal <?php echo implode(" ", $css_classes) ?>" id="datetime" name="<?php echo $name . $formset_index; ?>"
       value="<?php echo $value; ?>" placeholder="<?php echo ucfirst(str_replace("_", " ", $name)); ?>"/>