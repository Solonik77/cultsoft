<h2>Добавление партнёра</h2>
<p><a href="/admin/Managepartners/admin" class="button"><span>Управление информацией о партнёрах</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelpartners' => $modelpartners,
        'modelpartnersContent' => $modelpartnersContent,
        'update' => false,
        ));
?>