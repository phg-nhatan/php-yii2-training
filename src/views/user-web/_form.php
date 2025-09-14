<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var \app\models\User $model */
/** @var \yii\bootstrap5\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'email')->input('email') ?>
<?= $form->field($model, 'role')->dropDownList(['admin'=>'Admin','user'=>'User']) ?>
<?= $form->field($model, 'status')->dropDownList([1=>'Active',0=>'Inactive']) ?>
<?= $form->field($model, 'password')->passwordInput() ?>

<div class="mt-3">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
        ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>