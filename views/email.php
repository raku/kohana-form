<label for="email">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>

<input type="email" class="<?php echo implode(" ", $css_classes) ?>" id="email" name="<?php echo $name; ?>"
       value="<?php echo $value; ?>"/>