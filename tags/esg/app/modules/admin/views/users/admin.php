<h2>Управление пользователями</h2>
<div class="grid_12">
<p><a href="/admin/users/create" class="button"><span>Создать нового пользователя <img src="/images/admin/plus-small.gif" alt="Создать нового пользователя в система" /></span> </a> &nbsp; <br /></p>

<div class="module">
    <h2><span>Список пользователей системы</span> </h2>
    <div class="module-table-body">
<table class="tablesorter">
  <thead>
  <tr>
    <th><?php echo $sort->link('id')?></th>
    <th><?php echo $sort->link('login')?></th>
    <th><?php echo $sort->link('email')?></th>
    <th><?php echo $sort->link('role')?></th>
    <th><?php echo $sort->link('first_name')?></th>
    <th><?php echo $sort->link('last_name')?></th>
	<th>Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n => $model): ?>
  <tr class="<?php echo $n % 2?'even':'odd';
?>">
    <td><?php echo CHtml::link($model->id, array('update', 'id' => $model->id))?></td>
    <td><?php echo CHtml::encode($model->login)?></td>
    <td><?php echo CHtml::encode($model->email)?></td>
    <td><?php echo CHtml::encode($model->role)?></td>
    <td><?php echo CHtml::encode($model->first_name)?></td>
    <td><?php echo CHtml::encode($model->last_name)?></td>
    <td>
      <?php echo CHtml::link('Редактировать', array('update', 'id' => $model->id))?>
      <?php
if (Yii::app()->user->id != $model->id) {
    echo CHtml::linkButton('Удалить', array('submit' => '',
            'params' => array('command' => 'delete', 'id' => $model->id),
            'confirm' => "Вы правда хотите удалить пользователя {$model->login}?"));
}

?>
	</td>
  </tr>
<?php endforeach?>
  </tbody>
  <tfoot>
  <tr>
  <td colspan="7">
	<?php $this->widget('CLinkPager', array('pages' => $pages))?>
  </td>
  </tr>
  </tfoot>
</table>


</div>
</div>
</div>