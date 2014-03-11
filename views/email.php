<label for="email">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>

<input type="email" class="<?php echo implode(" ", $css_classes) ?>" id="email" name="<?php echo $name . $formset_index; ?>"
       value="<?php echo $value; ?>"/>