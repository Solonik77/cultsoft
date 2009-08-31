<h2>Управление информацией о сотрудниках</h2>
<div class="grid_12">
<p><a href="/admin/Manageteam/create" class="button"><span>Добавить нового сотрудника <img src="/images/admin/plus-small.gif" alt="Добавить нового сотрудника" /></span> </a> &nbsp; <br /></p>

<div class="module">
    <h2><span>Сотрудники</span> </h2>
    <div class="module-table-body">
    <?php if (count($models) > 0): ?>
<table class="tablesorter">
  <thead>
  <tr>
    <th width="1%">#</th>
    <th>Фото</th>
    <th><?php echo $sort->link('name')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('fancy_url')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('is_show')?></th>
	<th width="10%">Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n => $model): ?>
  <tr class="<?php echo ($n % 2) ? 'even' : 'odd' ?>">
    <td><?php echo CHtml::link($model->id, array('update', 'id' => $model->id))?></td>
    <td width="1%">
	<?php if(file_exists(Yii::app()->params['storage']['team_photos'] . $model->id . '.jpg')):?>
	<img src="/static/images/photos/team/<?php echo $model->id . '.jpg?' . time()?>" alt="<?php echo CHtml::encode($model->team_content[0]->name)?>" />
	<?php else: ?>
	<img src="/static/images/photos/team/person.jpg" alt="<?php echo CHtml::encode($model->team_content[0]->name)?>" />
	<?php endif;?>

	</td>
    <td class="left">
    <big><strong><?php echo CHtml::encode($model->team_content[0]->name)?></strong></big><br />
	<?php echo $model->team_content[0]->post?>
    </td>
	<td><?php echo CHtml::encode($model->fancy_url)?></td>
	<td><?php echo ($model->is_show) ? 'виден' : 'не виден' ?></td>
    <td nowrap="nowrap">
	  <?php echo CHtml::link(CHtml::image('/images/admin/pencil.gif', 'Редактировать'), array('update', 'id' => $model->id), array('name' => "Редактировать новость - " . CHtml::encode($model->team_content[0]->name)))?> &nbsp;
      <?php echo CHtml::linkButton(CHtml::image('/images/admin/bin.gif', 'Удалить'), array('submit' => '', 'params' => array('command' => 'delete', 'id' => $model->id), 'confirm' => "Вы действительно хотите удалить новость - " . CHtml::encode($model->team_content[0]->name) . "?"))?>
	</td>
  </tr>
<?php endforeach ?>
  </tbody>
  <tfoot>
  <tr>
  <td colspan="8">
	<?php $this->widget('CLinkPager', array('pages' => $pages))?>
  </td>
  </tr>
  </tfoot>
</table>
	<?php else: ?>
	<span class="notification n-attention">Информации о сотрудниках пока нет</span>
	<?php endif ?>

</div>
</div>
</div>
