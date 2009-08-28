<h2>Управление статичными страницами</h2>
<div class="grid_12">
<p><a href="/admin/manageStaticPages/create" class="button"><span>Создать новую страницу <img src="/images/admin/plus-small.gif" alt="Создать новую страницу" /></span> </a> &nbsp; <br /></p>

<div class="module">
    <h2><span>Статичные страницы</span> </h2>
    <div class="module-table-body">
    <?php if (count($models) > 0): ?>
<table class="tablesorter">
  <thead>
  <tr>
    <th width="1%">#</th>
    <th><?php echo $sort->link('title')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('login')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('fancy_url')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('date_updated')?></th>
	<th width="10%">Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n => $model): ?>
  <tr class="<?php echo ($n % 2) ? 'even' : 'odd' ?>">
    <td><?php echo CHtml::link($model->id, array('update', 'id' => $model->id))?></td>
    <td class="left">
	<?php if ($model->id == '1'):?>
	<?=CHtml::image('/images/admin/home.png', 'Домашняя страница')?>
	<?php endif;
    ?>
	<?php echo CHtml::encode($model->static_pages_content[0]->title)?></td>
    <td><?php echo CHtml::encode($model->users->login)?></td>
	<td><?php echo CHtml::encode($model->fancy_url)?></td>
	<td><?php echo CHtml::encode(date::full($model->date_updated))?></td>
    <td nowrap="nowrap">
	  <?php echo CHtml::link(CHtml::image('/images/admin/pencil.gif', 'Редактировать'), array('update', 'id' => $model->id), array('title' => "Редактировать страницу - " . CHtml::encode($model->static_pages_content[0]->title)))?> &nbsp;
      <?php if ($model->id != '1'):?>
      <?php echo CHtml::linkButton(CHtml::image('/images/admin/bin.gif', 'Удалить'), array('submit' => '', 'params' => array('command' => 'delete', 'id' => $model->id), 'confirm' => "Вы действительно хотите удалить страницу - " . CHtml::encode($model->static_pages_content[0]->title) . "?"))?>
	  <?php endif ?>
	</td>
  </tr>
<?php endforeach ?>
  </tbody>
  <tfoot>
  <tr>
  <td colspan="6">
	<?php $this->widget('CLinkPager', array('pages' => $pages))?>
  </td>
  </tr>
  </tfoot>
</table>
	<?php else: ?>
	<span class="notification n-attention">Страниц на сайте пока нет</span>
	<?php endif ?>
</div>
</div>
</div>
