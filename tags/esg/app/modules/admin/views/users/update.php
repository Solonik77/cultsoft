<h2>Редактирование профиля <?php echo $model->first_name ?> <?php echo $model->last_name ?> (<?php echo $model->login ?>)</h2>

<p>
<a href="/admin/users/admin" class="button"><span>Управление пользователями</span> </a> &nbsp;
<a href="/admin/users/create" class="button"><span>Создание пользователя</span> </a> &nbsp;
<br /></p>

<?php echo $this->renderPartial('_form', array('model' => $model,
        'update' => true,
        ));

?>