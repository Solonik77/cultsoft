<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tinybrowser/tb_tinymce.js.php"></script>
<?php include(DOC_ROOT . '/assets/tiny_mce_init.js')?>
<div class="grid_12">
  <div class="module">
    <h2><span>Партнёр</span></h2>
    <div class="module-body">
      <p>Поля с <span class="required">*</span> обязательны для заполнения.</p>
      <?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data'))?>
	  <?php echo CHtml::errorSummary($modelpartners)?>

      <fieldset><legend>Файл логотипа компании на сайт</legend>
        <p>
	<?php if(file_exists(Yii::app()->params['storage']['partner_logos'] . $modelpartners->id . '.jpg')):?>
	<img src="/static/images/partners/logos/<?php echo $modelpartners->id . '.jpg?' . time()?>" />
	<?php else: ?>
	<img src="/static/images/partners/logos/no_logo.jpg" alt="" />
	<?php endif;?>
		</p>
		<p><?php echo CHtml::activeLabelEx($modelpartners, 'logo') ?> <?php echo CHtml::activeFileField($modelpartners, 'logo'); ?></p>
                <p class='hint'>Допустимый формат файла: jpg, png, gif. Размер изображения будет подогнан под соотношение не более 400px по ширине и не более 100px по высоте.</p>
      </fieldset>

      <?php foreach($this->websiteLanguages as $key => $value): ?>
      <fieldset>
      <legend><img src='<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/lang_icons/<?=$key?>.gif' />
      <?=$value['name']?>
      </legend>
      <?php echo CHtml::errorSummary($modelpartnersContent[$key])?>
      <p> <?php echo CHtml::activeLabelEx($modelpartnersContent[$key], 'name[' . $key . ']')?> <?php echo CHtml::activeTextField($modelpartnersContent[$key], 'name[' . $key . ']', array('size' => 60, 'maxlength' => 255))?> </p>
      <p> <?php echo CHtml::activeLabelEx($modelpartnersContent[$key], 'description[' . $key . ']')?> <?php echo CHtml::activeTextArea($modelpartnersContent[$key], 'description[' . $key . ']', array('rows' => 4, 'cols' => 50))?> </p>
      </fieldset>
      <?php endforeach ?>
	 <p>
<?php echo CHtml::activeLabelEx($modelpartners, 'is_show') ?>
    <select name="partners[is_show]">
        <option <?php if ($modelpartners->is_show) {
    ?>selected<?php }
?> value='1'>виден</option>
        <option <?php if (!$modelpartners->is_show) {
    ?>selected<?php }
?> value='0'>скрыть</option>
    </select>
</p>

<p> <?php echo CHtml::activeLabelEx($modelpartners, 'site_url')?> <?php echo CHtml::activeTextField($modelpartners, 'site_url', array('size' => 60, 'maxlength' => 100))?><br />


      <p> <?php echo CHtml::activeLabelEx($modelpartners, 'fancy_url')?> <?php echo CHtml::activeTextField($modelpartners, 'fancy_url', array('size' => 60, 'maxlength' => 100))?><br />
        <span class='hint'>Необязательное поле для генерации оптимизированных под SEO ссылок вида <code>http://example.com/fancy-page-name</code>. Если оставить пустым, будет сформировано автоматически из имени партнёра.</span> </p>

      <p class="action"> <?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать')?>
	  <?php if ($update AND $modelpartners->id != '1') {
    ?>  <?php echo CHtml::linkButton('<img src="/images/admin/cross-small.gif"  /> Удалить', array('submit' => '', 'params' => array('command' => 'delete', 'id' => $modelpartners->id), 'confirm' => "Вы действительно хотите удалить новость?"))?>  <?php }
?>
    </div>
    <?php echo CHtml::endForm()?> </div>
</div>