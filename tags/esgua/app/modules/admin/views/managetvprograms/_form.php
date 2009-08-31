<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tinybrowser/tb_tinymce.js.php"></script>
<?php include(DOC_ROOT . 'assets/tiny_mce_init.js')?>
<div class="grid_12">
  <div class="module">
    <h2><span>ТВ-программа</span></h2>
    <div class="module-body">
      <p>Поля с <span class="required">*</span> обязательны для заполнения.</p>
      <?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data'))?>
	  <?php echo CHtml::errorSummary($modeltvprograms)?>

      <fieldset><legend>Файл презентационного ролика</legend>
        <p>
	<?php if(file_exists(Yii::app()->params['storage']['tv_program_promo_videos'] . $modeltvprograms->id . '.flv')):?>
	
    
    <?php echo html::videoplayer(array(
    'file' => "media/tv_programs/promo/" . $modeltvprograms->id . ".flv",
    'image' => "images/tv_programs/promo/" . $modeltvprograms->id . ".jpg",
    'width' => 470, 'height' => 320, 'div' => 'promoVideo'
    )) ?>

	<?php else: ?>
	Пока нет видеофайла
	<?php endif;?>
		</p>
		<p><?php echo CHtml::activeLabelEx($modeltvprograms, 'promo_video') ?> <?php echo CHtml::activeFileField($modeltvprograms, 'promo_video', array('accept' => '*.flv')); ?></p>
                <p class='hint'>Допустимый формат файла: flv.</p>
      </fieldset>

      <?php foreach($this->websiteLanguages as $key => $value): ?>
      <fieldset>
      <legend><img src='<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/lang_icons/<?=$key?>.gif' />
      <?=$value['name']?>
      </legend>
      <?php echo CHtml::errorSummary($modeltvprogramsContent[$key])?>
      <p> <?php echo CHtml::activeLabelEx($modeltvprogramsContent[$key], 'name[' . $key . ']')?> <?php echo CHtml::activeTextField($modeltvprogramsContent[$key], 'name[' . $key . ']', array('size' => 60, 'maxlength' => 255))?> </p>
      <p> <?php echo CHtml::activeLabelEx($modeltvprogramsContent[$key], 'description[' . $key . ']')?> <?php echo CHtml::activeTextArea($modeltvprogramsContent[$key], 'description[' . $key . ']', array('rows' => 4, 'cols' => 50))?> </p>
      </fieldset>
      <?php endforeach ?>
	 <p>
<?php echo CHtml::activeLabelEx($modeltvprograms, 'is_show') ?>
    <select name="tvprograms[is_show]">
        <option <?php if ($modeltvprograms->is_show) {
    ?>selected<?php }
?> value='1'>видна</option>
        <option <?php if (!$modeltvprograms->is_show) {
    ?>selected<?php }
?> value='0'>скрыть</option>
    </select>
</p>

      <p> <?php echo CHtml::activeLabelEx($modeltvprograms, 'fancy_url')?> <?php echo CHtml::activeTextField($modeltvprograms, 'fancy_url', array('size' => 60, 'maxlength' => 100))?><br />
        <span class='hint'>Необязательное поле для генерации оптимизированных под SEO ссылок вида <code>http://example.com/fancy-page-name</code>. Если оставить пустым, будет сформировано автоматически из имени программы.</span> </p>

      <p class="action"> <?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать')?>
	  <?php if ($update AND $modeltvprograms->id != '1') {
    ?>  <?php echo CHtml::linkButton('<img src="/images/admin/cross-small.gif"  /> Удалить', array('submit' => '', 'params' => array('command' => 'delete', 'id' => $modeltvprograms->id), 'confirm' => "Вы действительно хотите удалить новость?"))?>  <?php }
?>
    </div>
    <?php echo CHtml::endForm()?> </div>
</div>