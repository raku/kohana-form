<label for="checkbox">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>

<input style="width:20px;box-shadow: none;"
       onclick="window.document.getElementById('hidden-value').value=Math.abs(window.document.getElementById('hidden-value').value - 1)"
       class="checkbox" type="checkbox"
       class="<?php echo implode(" ", $css_classes) ?>"
       id="checkbox"
    <?php echo (bool)$value ? "checked=checked" : ""; ?>/>
<input id="hidden-value" type="hidden" name="<?php echo $name . $formset_index; ?>"
       value="<?php echo (bool)$value ? 1 : 0; ?>"/>