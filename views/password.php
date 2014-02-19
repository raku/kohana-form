<label for="password">
    <?php echo ucfirst($name); ?>
</label>

<input type="password" class="<?php echo implode(" ", $css_classes) ?>" id="password" name="<?php echo $name; ?>"
       value="<?php echo $value; ?>"/>