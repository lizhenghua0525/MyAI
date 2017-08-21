<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Citc ProductAI';
?>

<?php if($page_info): ?>
	<div class="alert alert-danger">
		<?= Html::encode("$page_info")?>
	</div>
<?php endif ?>
	
<div class="site-index">
	<p>图片搜索</p>
	
	<div class="col-lg-5">
		<?php $form = ActiveForm::begin(['id' => 'pick-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
			<?= $form->field($model, 'file')->fileInput()?>
			
			<div class="form-group">
				<?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name' => 'login-button'])?>
			</div>
			<?php ActiveForm::end(); ?>
	</div>
</div>
<br />

<?php if($search_result): ?>

<div class="site-index">
	<p>搜索结果</p>
	
	<?php foreach($search_result as $result): ?>
		<?= Html::img($result)?>
	<?php endforeach; ?>
</div>
<?php endif ?>
