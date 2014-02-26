<?php if (!empty($value)): ?>
    <a href="<?php echo URL::base() . $value; ?>">
        <?php echo URL::base() . $value; ?>
    </a>
<?php endif; ?>
<label for="file">
    <?php echo ucfirst($name); ?>
</label>

<input type="file" class="<?php echo implode(" ", $css_classes) ?>" id="file" name="<?php echo $name; ?>"/>
