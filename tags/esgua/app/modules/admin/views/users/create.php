<h2>Создание нового пользователя</h2>
<p><a href="/admin/users/admin" class="button"><span>Управление пользователями</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('model' => $model,
        'update' => false,
        ));

?>