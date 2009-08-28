<h2>Редактирование информации о <?php echo $modelPartnersContent['uk']->name ?></h2>
<p><a href="/admin/managePartners/admin" class="button"><span>Управление информацией о партнёрах</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelPartners' => $modelPartners,
        'modelPartnersContent' => $modelPartnersContent,
        'update' => true,
        )) ?>