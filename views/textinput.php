<label for="text">
    <?php echo ucfirst($name); ?>
</label>

<input type="text" id="text" class="<?php echo implode(" ", $css_classes) ?>" name="<?php echo $name; ?>"
       value="<?php echo $value; ?>"/>