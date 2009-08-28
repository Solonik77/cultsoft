<h2>Редактирование страницы <?php echo $modelStaticPagesContent['uk']->title ?></h2>
<p><a href="/admin/manageStaticPages/admin" class="button"><span>Управление статичными страницами</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelStaticPages' => $modelStaticPages,
        'modelStaticPagesContent' => $modelStaticPagesContent,
        'attachedFiles' => $attachedFiles,
		'attachedFilesCount' => $attachedFilesCount,
		'update' => true,
        )) ?>