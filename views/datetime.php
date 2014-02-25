<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/Kalendae/0.4.1/kalendae.standalone.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Kalendae/0.4.1/kalendae.css"/>

<label for="datetime">
    <?php echo ucfirst($name); ?>
</label>

<input type="text" class="auto-kal <?php echo implode(" ", $css_classes) ?>" id="datetime" name="<?php echo $name; ?>"
       value="<?php echo $value; ?>"/>