<script type="text/javascript" src="/assets/tinybrowser/tb_standalone.js.php"></script>
<div class="grid_7">
<a href="/admin/Managenews/create/" class="dashboard-module"> <img src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/admin/big_icon_addnews.png" width="64" height="64" alt="edit" /> <span>Добавить новость</span> </a>
<a href="/admin/Managestaticpages/create/" class="dashboard-module"> <img src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/admin/Crystal_Clear_write.gif" width="64" height="64" alt="edit" /> <span>Создать статическую страницу</span> </a>
<a href="/admin/" onclick="tinyBrowserPopUp('file','element'); return false;" class="dashboard-module"> <img src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/admin/big_icon_upload.png" width="64" height="64" alt="edit" /> <span>Загрузить файл</span> </a>
  <div style="clear: both"></div>
    <div class="module">
      <h2><span>Структура сайта</span></h2>
      <div class="module-body">
<?php
$this->widget('CTreeView', array('htmlOptions' => array('class' => 'treeview-black'),
        'data' => array(
            array('text' => '<img width="16" height="16" src="/images/admin/module-icon-static-page.png" />  <a href="/admin/Managestaticpages/update/id/1" title="Перейти к редактированию главной страницы">Главная страница</a>', 'id' => 'siteStructure', 'expanded' => true, 'hasChildren' => true, 'children' => $this->sitetree
                ))));

?>
	</div>
	</div>
  <div style="clear: both"></div>
</div>
<div class="grid_5">
  <div class="module">
    <h2><span>Учётная запись</span></h2>
    <div class="module-body">
      <p> <strong>Пользователь: </strong>
        <?=Yii::app()->user->name?>
        <br />
        <strong>IP:
        <?= Yii::app()->getRequest()->getUserHostAddress()?>
        </strong> </p>
      <a href="/admin/users/update/id/<?php echo Yii::app()->user->id?>" class="dashboard-module"><img src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/admin/Crystal_Clear_user.gif" width="64" height="64" alt="edit" /><span>Мой профиль</span> </a> </div>
  </div>
  <div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>
