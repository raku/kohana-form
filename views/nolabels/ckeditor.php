<script src="http://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.3.2/ckeditor.js"></script>

<label for="password">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>

<textarea name="<?php echo $name ?>" class="<?php echo implode(" ", $css_classes) ?>"
          id="<?php echo $name . $formset_index ?>" cols="30"
          rows="10"><?php echo $value ?></textarea>

<script>
    CKEDITOR.replace("<?php echo $name . $formset_index ?>");
</script>