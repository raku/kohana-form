<label for="text">
    <?php echo ucfirst(str_replace("_", " ", $name)); ?>
</label>

<select id="select" multiple="multiple" class="<?php echo implode(" ", $css_classes) ?>"
        name="<?php echo $name . $formset_index; ?>[]"/>
<option value="#">---</option>

<?php foreach ($choices as $key => $cvalue): ?>
    <option <?php echo in_array($key, $value) ? 'selected="selected"' : "" ?>
        value="<?php echo $key ?>"><?php echo $cvalue ?></option>
<?php endforeach; ?>
</select>