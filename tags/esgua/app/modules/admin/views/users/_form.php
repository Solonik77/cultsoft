<div class="grid_12">
  <div class="module">
    <h2><span>Данные пользователя</span></h2>
    <div class="module-body">
<p>Поля с <span class="required">*</span> обязательны для заполнения.</p>

<?php echo CHtml::beginForm();
?>

<?php echo CHtml::errorSummary($model);
?>

<p>
<?php echo CHtml::activeLabelEx($model, 'password');
?>
<?php echo CHtml::activeTextField($model, 'password', array('size' => 32, 'maxlength' => 32, 'style' => "font-weight: bold; font-family: serif"));
?>
</p>
<p>
<?php echo CHtml::activeLabelEx($model, 'email');
?>
<?php echo CHtml::activeTextField($model, 'email', array('size' => 60, 'maxlength' => 255));
?>
</p>
<p>
<?php echo CHtml::activeLabelEx($model, 'first_name');
?>
<?php echo CHtml::activeTextField($model, 'first_name', array('size' => 60, 'maxlength' => 255));
?>
</p>
<p>
<?php echo CHtml::activeLabelEx($model, 'last_name');
?>
<?php echo CHtml::activeTextField($model, 'last_name', array('size' => 60, 'maxlength' => 255));
?>
</p>

<p>
<?php echo CHtml::activeLabelEx($model, 'role');
?>
    <select id="Users_role" name="Users[role]">
        <option <?php if ($model->role == 'admin') {
    ?>selected<?php }
?>>admin</option>
        <option <?php if ($model->role == 'user') {
    ?>selected<?php }
?>>user</option>
    </select>
</p>

<p class="action">
<?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать');
?>
</p>


<?php echo CHtml::endForm();
?>

</div>
</div>
</div>
