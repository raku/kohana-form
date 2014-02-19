<label for="datetime">
    <?php echo ucfirst($name); ?>
</label>

<input type="text" class="<?php echo implode(" ", $css_classes) ?>" id="datetime" name="<?php echo $name; ?>"
       value="<?php echo $value; ?>"/>