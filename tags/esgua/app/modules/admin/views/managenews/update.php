<h2>Редактирование новости <?php echo $modelnewsContent['uk']->title ?></h2>
<p><a href="/admin/Managenews/admin" class="button"><span>Управление новостями</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelnews' => $modelnews,
        'modelnewsContent' => $modelnewsContent,
        'update' => true,
        )) ?>