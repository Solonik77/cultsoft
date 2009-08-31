<h2>Добавление сотрудника</h2>
<p><a href="/admin/Manageteam/admin" class="button"><span>Управление информацией о сотрудниках</span> </a> &nbsp; <br /></p>
<?php echo $this->renderPartial('_form', array('modelteam' => $modelteam,
        'modelteamContent' => $modelteamContent,
        'update' => false,
        ));
