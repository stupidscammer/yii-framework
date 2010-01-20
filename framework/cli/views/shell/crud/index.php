<?php
/**
 * This is the template for generating the index view for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $modelClass: the model class name
 * - $columns: a list of column schema objects
 */
?>
<?php
echo "<?php\n";
$label=$this->class2name($modelClass,true);
$route=$modelClass.'/index';
$route[0]=strtolower($route[0]);
echo "\$this->breadcrumbs=array(
	'$label',
);\n";
?>

Yii::app()->clientScript->registerScript('search', "
$('#search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<h1>List <?php echo $modelClass; ?></h1>

<ul class="actions">
	<li><?php echo "<?php echo CHtml::link('Create {$modelClass}',array('create')); ?>"; ?></li>
	<li><?php echo "<?php echo CHtml::link('Manage {$modelClass}',array('admin')); ?>"; ?></li>
	<li><?php echo "<?php echo CHtml::link('Search {$modelClass}','#',array('id'=>'search-button')); ?>"; ?></li>
</ul><!-- actions -->

<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$model->search(),
	'itemView'=>'_view',
)); ?>
