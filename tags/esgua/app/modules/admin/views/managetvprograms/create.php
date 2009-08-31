<h2>Добавление программы</h2>
<p><a href="/admin/Managetvprograms/admin" class="button"><span>Управление информацией о программах</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modeltvprograms' => $modeltvprograms,
        'modeltvprogramsContent' => $modeltvprogramsContent,
        'update' => false,
        ));
?>