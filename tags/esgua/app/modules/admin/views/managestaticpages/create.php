<h2>Создание новой статичной страницы</h2>
<p><a href="/admin/Managestaticpages/admin" class="button"><span>Управление статичными страницами</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelStaticPages' => $modelStaticPages,
        'modelStaticPagesContent' => $modelStaticPagesContent,
        'attachedFiles' => $attachedFiles,
		'attachedFilesCount' => $attachedFilesCount,
		'update' => false,
        ));
?>