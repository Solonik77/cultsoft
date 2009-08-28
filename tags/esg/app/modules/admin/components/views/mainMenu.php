<ul id="nav">
<?php foreach($items as $item): ?>
<li><?php echo CHtml::link($item['label'], $item['url'],
    $item['active'] ? array('id' => 'active') : array());
?></li>
<?php endforeach;
?>
</ul>
