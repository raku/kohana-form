<input type="text" id="text" class="<?php echo implode(" ", $css_classes) ?>"
       name="<?php echo $name . $formset_index; ?>"
       value="<?php echo $value; ?>" placeholder="<?php echo ucfirst(str_replace("_", " ", $name)); ?>"/>