<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/tinybrowser/tb_tinymce.js.php"></script>
<?php include(DOC_ROOT . 'assets/tiny_mce_init.js')?>
<div class="grid_12">
  <div class="module">
    <h2><span>Новость</span></h2>
    <div class="module-body">
      <p>Поля с <span class="required">*</span> обязательны для заполнения.</p>
      <?php echo CHtml::beginForm()?>
	  <?php echo CHtml::errorSummary($modelnews)?>
      <?php foreach($this->websiteLanguages as $key => $value): ?>
      <fieldset>
      <legend><img src='<?php echo Yii::app()->request->getBaseUrl(true) ?>/images/lang_icons/<?=$key?>.gif' />
      <?=$value['name']?>
      </legend>
      <?php echo CHtml::errorSummary($modelnewsContent[$key])?>
      <p> <?php echo CHtml::activeLabelEx($modelnewsContent[$key], 'title[' . $key . ']')?> <?php echo CHtml::activeTextField($modelnewsContent[$key], 'title[' . $key . ']', array('size' => 60, 'maxlength' => 255))?> </p>
      <p> <?php echo CHtml::activeLabelEx($modelnewsContent[$key], 'content[' . $key . ']')?> <?php echo CHtml::activeTextArea($modelnewsContent[$key], 'content[' . $key . ']', array('rows' => 15, 'cols' => 50))?> </p>
      </fieldset>
      <?php endforeach ?>
      <p> <?php echo CHtml::activeLabelEx($modelnews, 'date_publish')?> <?php echo CHtml::activeTextField($modelnews, 'date_publish', array('size' => 30, 'maxlength' => 100, 'class' => 'icon-date'))?>

<script type="text/javascript">
    Calendar.setup({
      inputField     :    "news_date_publish",     // id of the input field
      ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
	  timeFormat     :    "24",
	  showsTime      :    true,
      singleClick    :    true
    });
</script>
      </p>
	        <p>
<?php echo CHtml::activeLabelEx($modelnews, 'is_published') ?>
    <select id="news_is_published" name="news[is_published]">
        <option <?php if ($modelnews->is_published) {
    ?>selected<?php }
?> value='1'>опубликована</option>
        <option <?php if (!$modelnews->is_published) {
    ?>selected<?php }
?> value='0'>скрыта</option>
    </select>
</p>

      <p> <?php echo CHtml::activeLabelEx($modelnews, 'fancy_url')?> <?php echo CHtml::activeTextField($modelnews, 'fancy_url', array('size' => 60, 'maxlength' => 100))?><br />
        <span class='hint'>Необязательное поле для генерации оптимизированных под SEO ссылок вида <code>http://example.com/fancy-page-name</code>. Если оставить пустым, будет сформировано автоматически из заголовка.</span> </p>


      <p class="action"> <?php echo CHtml::submitButton($update ? 'Сохранить' : 'Создать')?>
	  <?php if ($update AND $modelnews->id != '1') {
    ?>  <?php echo CHtml::linkButton('<img src="/images/admin/cross-small.gif"  /> Удалить', array('submit' => '', 'params' => array('command' => 'delete', 'id' => $modelnews->id), 'confirm' => "Вы действительно хотите удалить новость?"))?>  <?php }
?>
    </div>
    <?php echo CHtml::endForm()?> </div>
</div>