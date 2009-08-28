<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSS Reset -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/admin/reset.css" media="screen" />
<!-- Fluid 960 Grid System - CSS framework -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/admin/grid.css" media="screen" />
<!-- IE Fixes for the Fluid 960 Grid System -->
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/admin/ie6.css" media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/admin/ie.css" media="screen" /><![endif]-->
<!-- Main stylesheet -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/admin/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/admin/theme-blue.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/css/form.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/calendar/calendar-blue.css" title="win2k-cold-1" />
<title><?php echo $this->pageTitle ?></title>

<?php
/*
* Hide flash messages
*/

Yii::app()->clientScript->registerScript(
   'hideNotifications',
   //'$(".notification").animate({opacity: 0.5}, 15000).fadeOut("slow");',
   '',
   CClientScript::POS_READY
);

?>

<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/swfobject.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/calendar/calendar-en.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->getBaseUrl(true) ?>/assets/calendar/calendar-setup.js"></script>

</head>
<body>
<!-- Header -->
<div id="header">
  <!-- Header. Status part -->
  <div id="header-status">
    <div class="container_12">
      <div class="grid_8"> <span id="text-invitation">Добро пожаловать, <?php echo Yii::app()->user->name ?>!</span></div>
      <div class="grid_4"> <a href="/site/logout" id="logout"> Выйти </a> </div>
    </div>
    <div style="clear:both;"></div>
  </div>
  <!-- End #header-status -->
  <!-- Header. Main part -->
  <div id="header-main">
    <div class="container_12">
      <div class="grid_12">
        <div id="logo">
<?php
$this->widget('application.modules.admin.components.MainMenu', array('items' => array(
            array('label' => 'Панель управления', 'url' => array('/admin/')),
            array('label' => 'Перейти на сайт', 'url' => array('/site')),
            )));

?>
        </div>
        <!-- End. #Logo -->
      </div>
      <!-- End. .grid_12-->
      <div style="clear: both;"></div>
    </div>
    <!-- End. .container_12 -->
  </div>
  <!-- End #header-main -->
  <div style="clear: both;"></div>
  <!-- Sub navigation -->
  <div id="subnav">
    <div class="container_12">
      <div class="grid_12">
        <ul>
          <li><a href="/admin/manageNews">Новости</a></li>
          <li><a href="/admin/manageStaticPages/">Страницы</a></li>
          <li><a href="/admin/manageTeam/">Cотрудники</a></li>
          <li><a href="/admin/manageTvPrograms/">Программы</a></li>
          <li><a href="/admin/managePartners/">Партнёры</a></li>
          <li><a href="/admin/users/">Пользователи системы</a></li>
        </ul>
      </div>
      <!-- End. .grid_12-->
    </div>
    <!-- End. .container_12 -->
    <div style="clear: both;"></div>
  </div>
  <!-- End #subnav -->
</div>
<!-- End #header -->
<div class="container_12">
<?php if (Yii::app()->user->hasFlash('success')):?><span class="notification n-success"><?php echo Yii::app()->user->getFlash('success') ?></span><?php endif ?>
<?php if (Yii::app()->user->hasFlash('infofmation')):?><span class="notification n-information"><?php echo Yii::app()->user->getFlash('infofmation') ?></span><?php endif ?>
<?php if (Yii::app()->user->hasFlash('attention')):?><span class="notification n-attention"><?php echo Yii::app()->user->getFlash('attention') ?></span><?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')):?><span class="notification n-error"><?php echo Yii::app()->user->getFlash('error') ?></span><?php endif ?>
<?php
                if (isset($this->crumbs)) {
                    $crumbs = array(array('name' => 'Панель управления', 'url' => array('/admin/')));
                    if (count($this->crumbs) > 0) {
                        $crumbs = array_merge($crumbs, $this->crumbs);
                    }
                    if (count($crumbs) > 1) {
                        $this->widget('application.components.Breadcrumbs', array('crumbs' => $crumbs ,
                                'delimiter' => ' &rarr; '
                                ));
                    }
                }

                ?>

<?php echo $content ?>
<div style="clear:both"></div>
</div>
<!-- Footer -->
<div id="footer">
  <div class="container_12">
    <div class="grid_12">
      <p>&copy; 2009 ESG.</p>
    </div>
  </div>
  <div style="clear:both;"></div>
</div>
<!-- End #footer -->
</body>
</html>