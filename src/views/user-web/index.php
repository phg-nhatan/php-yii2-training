<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Users';
?>

<div class="d-flex justify-content-between mb-3">
    <h3><?= Html::encode($this->title) ?></h3>
    <?= Html::a('Create User', ['create'], ['class'=>'btn btn-success']) ?>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'username',
        'email:email',
        'role',
        [
            'attribute'=>'status',
            'value'=>fn($m)=>$m->status ? 'Active':'Inactive',
            'filter'=>[1=>'Active',0=>'Inactive'],
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>