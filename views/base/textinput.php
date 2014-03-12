<label for="text">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>

<input type="text" id="text" class="<?php echo implode(" ", $css_classes) ?>" name="<?php echo $name . $formset_index; ?>"
       value="<?php echo $value; ?>"/>