<?
/*


                    <p class=''><?php
$this->navigation()->breadcrumbs()->setSeparator(' » ');
echo $this->navigation()->breadcrumbs($this->topMenu)?></p>
<?php
echo $this->messages();

?> <?php
echo $this->layout()->content?>



<?php echo $this->render($this->sidebarBlocks)?>


<?php echo $this->navigation()->menu($this->footerMenu)?>

<?php
echo $this->projectTitle?>

<?php echo $this->navigation()->menu($this->topMenu)?>


<?php
	echo __('Welcome')?> <a
		href="<?php
		echo $this->url(array('profile_id' => $this->member->getId()), 'view_profile')?>"><?php
		echo $this->member->getField('first_name') . ' ' . $this->member->getField('last_name')?></a></li>
	<li><a
		href="<?php
		echo $this->url(array('module' => 'main', 'controller' => 'index', 'action' => 'index'))?>"><?php echo __('Go to homepage')?></a></li>
	<li><a
		href="<?php
		echo $this->url(array('module' => 'main', 'controller' => 'profile', 'action' => 'settings'))?>"><?php echo __('Settings')?></a></li>
	<li class="last"><a
		href="<?php
		echo $this->url(array('module' => 'main', 'controller' => 'profile', 'action' => 'logout'))?>"><?php echo __('Log Out')?></a></li>
        
        
        <h1><?php
echo __($this->pageTitle)?></h1>
<span class="right"><?php
echo __($this->pageDescription)?></span>
*/
?>