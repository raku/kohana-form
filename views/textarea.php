<label for="password">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>


<textarea name="<?php echo $name ?>" class="<?php echo implode(" ", $css_classes) ?>" id="<?php echo $name ?>" cols="30"
          rows="10"><?php echo $value ?></textarea>