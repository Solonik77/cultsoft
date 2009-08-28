<h2>Редактирование новости <?php echo $modelNewsContent['uk']->title ?></h2>
<p><a href="/admin/manageNews/admin" class="button"><span>Управление новостями</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelNews' => $modelNews,
        'modelNewsContent' => $modelNewsContent,
        'update' => true,
        )) ?>