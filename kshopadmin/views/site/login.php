<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\PjaxAsset;
PjaxAsset::register($this);

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';
$fieldOptions1 = [
	'options' => ['class' => 'form-group has-feedback'],
	'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];
$fieldOptions2 = [
	'options' => ['class' => 'form-group has-feedback'],
	'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
$js = <<<JS
$("document").ready(function(){
	
});
JS;
$this->registerJs($js);
?>

<div class="login-box">
	<div class="login-logo">
		<a href="#"><b>Lartik</b>Shop</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="login-box-msg">Sign in to start your session</p>
		<?php Pjax::begin([
			'formSelector' => '#login-form',
			'enablePushState' => false
		]);?>
		<?php
			$form = ActiveForm::begin([
				'id' => 'login-form',
				'enableClientValidation' => false,
				'action' => $routes['login_action'],
			]);
		?>
		<?= $form
			->field($model, 'u_username', $fieldOptions1)
			->label(false)
			->textInput(['placeholder' => $model->getAttributeLabel('u_username')])
		?>
		<?= $form
			->field($model, 'password', $fieldOptions2)
			->label(false)
			->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
		?>
		<div class="row">
			<div class="col-xs-8">
				<?= $form->field($model, 'rememberMe')->checkbox() ?>
			</div>
			<!-- /.col -->
			<div class="col-xs-4">
				<?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
			</div>
			<!-- /.col -->
		</div>
		<?php ActiveForm::end(); ?>
		<?php Pjax::end();?>
		<!-- /.social-auth-links -->
		<a href="#">忘记密码</a><br>
	</div>
	<!-- /.login-box-body -->
</div><!-- /.login-box -->
