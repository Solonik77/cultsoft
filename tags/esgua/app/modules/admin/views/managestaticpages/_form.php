<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tinybrowser/tb_tinymce.js.php"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/attached_files.js"></script>
<?php include(DOC_ROOT . 'assets/tiny_mce_init.js')?>

<?php
Yii::app()->clientScript->registerScript('uiControlShowFileManager',
    '   $("a.sFileOpen").toggle(
   	function(){
	 $("#sFileManagerList").show();
	 $("#sFileManager").slideDown("slow");
    	 return false;
	},
	function(){
     $("#sFileManagerList").hide();
	 $("#sFileManager").slideUp("slow");
    	 return false;
	}
   );',
    CClientScript::POS_READY
    );

?>
<div class="grid_12">
  <div class="module">
    <h2><span>Статическая страница</span></h2>
    <div class="module-body">
      <p>Поля с <span class="required">*</span> обязательны для заполнения.</p>
      <?php echo CHtml::beginForm()?>
	  <?php echo CHtml::errorSummary($modelStaticPages)?>
      <?php echo CHtml::hiddenField('elementType', 'static_page')?>
      <?php echo CHtml::hiddenField('elementId', Yii::app()->request->getQuery('id', 0))?>
<?php
if(Yii::app()->request->getQuery('id') != 1):
?>
	  <p>
	  <label for="sitetree_position">Родительская ветка в дереве сайта</label>
<?
$this->widget('application.modules.admin.components.sitetreeDropdown', array(
        'model' => sitetree::model(),
        'primaryId' => 1,
        'currentElementId' => (Yii::app()->request->getQuery('id')) ? Yii::app()->request->getQuery('id') : NULL,
));
?>
<br />
        <span class='hint'>Учитываются только статические страницы, специальные модули не содержат подразделов. Параметр влияет на формирование навигационного меню.</span> </p>
<?php
endif;
?>
	  <?php foreach($this->websiteLanguages as $key => $value): ?>
      <fieldset>
      <legend><img src='<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/lang_icons/<?=$key?>.gif' />
      <?=$value['name']?>
      </legend>
      <?php echo CHtml::errorSummary($modelStaticPagesContent[$key])?>
      <p> <?php echo CHtml::activeLabelEx($modelStaticPagesContent[$key], 'title[' . $key . ']')?> <?php echo CHtml::activeTextField($modelStaticPagesContent[$key], 'title[' . $key . ']', array('size' => 60, 'maxlength' => 255))?> </p>
      <p> <?php echo CHtml::activeLabelEx($modelStaticPagesContent[$key], 'content[' . $key . ']')?> <?php echo CHtml::activeTextArea($modelStaticPagesContent[$key], 'content[' . $key . ']', array('rows' => 15, 'cols' => 50))?> </p>
      </fieldset>
      <?php endforeach ?>
      <p> <?php echo CHtml::activeLabelEx($modelStaticPages, 'fancy_url')?> <?php echo CHtml::activeTextField($modelStaticPages, 'fancy_url', array('size' => 60, 'maxlength' => 100))?><br />
        <span class='hint'>Необязательное поле для генерации оптимизированных под SEO ссылок вида <code>http://example.com/fancy-page-name</code>. Если оставить пустым, будет сформировано автоматически из заголовка.</span> </p>
      <style>
      table td
      {
	  	padding: 4px;
	  }
      </style>
	  <table width="100%">
	  <tr>
	    <td nowrap width="20%"><p><a href="#" class="sFileOpen"><img src="/images/admin/icon-filemanager.png" /> Файлы страницы (<span id="attachedFilesCount"><?php echo $attachedFilesCount?></span> шт.)</a></p></td>
		<td>
		<?php if ($attachedFilesCount > 0 AND !$update):?>
		<span class="notification n-information">Внимание, вы создаёте новую страницу и в базе данных к ней уже присоедены некоторые файлы (возможные последствия того, что файл был уже добавлен, а страница после не сохранялась).<br /> Убедитесь в их необходимости либо удалите их из списка.</span>
		<?php endif;
?>
		<div id="sFileManagerList">
		<h4>Прикреплённые файлы</h4>
		<p class="hint">Здесь представлены файлы, которые на сайте отображаются списком ссылок для скачивания. Для прикрипления файла достаточно найти его в менеджере ниже и кликнуть по имени. <strong>Обязательно после загрузки и прикрипления файла задавайте осмысленное имя и, при желании, описание (для сохранения нажмите ссылку <code>Сохранить изменения</code>).</strong> </p>
			<div id="attachedFiles"><?php echo $attachedFiles ?></div>
		</div>
		</td>
	  </tr>
	  <tr>
	  <td colspan="2">
	   <iframe scrolling="auto"  allowtransparency="1" frameborder="1" id="sFileManager" width="100%" height="420" src="/assets/tinybrowser/tinybrowser.php?type=file&amp;folder=&amp;feid=element&amp;sFileManager=true"></iframe>
	  </td>
	  </tr>
	  </table>
	  <p class="action"> <?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать')?>
	  <?php if ($update AND $modelStaticPages->id != '1') {

    ?>  <?php echo CHtml::linkButton('<img src="/images/admin/cross-small.gif"  /> Удалить', array('submit' => '', 'params' => array('command' => 'delete', 'id' => $modelStaticPages->id), 'confirm' => "Вы действительно хотите удалить страницу?"))?>  <?php }

?>
    </div>
    <?php echo CHtml::endForm()?> </div>
    </div>
</div>