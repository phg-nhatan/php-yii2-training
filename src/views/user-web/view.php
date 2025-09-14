<?php
use yii\widgets\DetailView;
use yii\helpers\Html;

$this->title = "User #{$model->id}";
?>

<div class="mb-3">
    <?= Html::a('Update', ['update','id'=>$model->id], ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete','id'=>$model->id], [
        'class'=>'btn btn-danger',
        'data'=>['method'=>'post','confirm'=>'Delete this user?']
    ]) ?>
    <?= Html::a('Back', ['index'], ['class'=>'btn btn-secondary']) ?>
</div>

<?= DetailView::widget([
    'model'=>$model,
    'attributes'=>['id','username','email','role','status'],
]); ?>