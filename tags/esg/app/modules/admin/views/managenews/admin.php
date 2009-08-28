<h2>Управление новостями</h2>
<div class="grid_12">
<p><a href="/admin/manageNews/create" class="button"><span>Создать новость <img src="/images/admin/plus-small.gif" alt="Создать новость" /></span> </a> &nbsp; <br /></p>

<div class="module">
    <h2><span>Новости</span> </h2>
    <div class="module-table-body">
    <?php if (count($models) > 0): ?>
<table class="tablesorter">
  <thead>
  <tr>
    <th width="1%">#</th>
    <th><?php echo $sort->link('title')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('login')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('fancy_url')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('date_publish')?></th>
    <th width="10%" nowrap="nowrap"><?php echo $sort->link('is_published')?></th>
	<th width="10%">Действия</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n => $model): ?>
  <tr class="<?php echo ($n % 2) ? 'even' : 'odd' ?>">
    <td><?php echo CHtml::link($model->id, array('update', 'id' => $model->id))?></td>
    <td class="left">
	<?php echo CHtml::encode($model->news_content[0]->title)?></td>
    <td><?php echo CHtml::encode($model->users->login)?></td>
	<td><?php echo CHtml::encode($model->fancy_url)?></td>
	<td><?php echo CHtml::encode(date::full($model->date_publish))?></td>
	<td><?php echo ($model->is_published) ? 'опубликована' : 'скрыта' ?></td>
    <td nowrap="nowrap">
	  <?php echo CHtml::link(CHtml::image('/images/admin/pencil.gif', 'Редактировать'), array('update', 'id' => $model->id), array('title' => "Редактировать новость - " . CHtml::encode($model->news_content[0]->title)))?> &nbsp;
      <?php echo CHtml::linkButton(CHtml::image('/images/admin/bin.gif', 'Удалить'), array('submit' => '', 'params' => array('command' => 'delete', 'id' => $model->id), 'confirm' => "Вы действительно хотите удалить новость - " . CHtml::encode($model->news_content[0]->title) . "?"))?>
	</td>
  </tr>
<?php endforeach ?>
  </tbody>
  <tfoot>
  <tr>
  <td colspan="7">
	<?php $this->widget('CLinkPager', array('pages' => $pages))?>
  </td>
  </tr>
  </tfoot>
</table>
	<?php else: ?>
	<span class="notification n-attention">Новостей на сайте пока нет</span>
	<?php endif ?>

</div>
</div>
</div>
