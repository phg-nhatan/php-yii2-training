<?php
namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\data\Sort;
use yii\rest\Serializer;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items', // Data nằm trong 'items'
        'linksEnvelope'      => '_links',
        'metaEnvelope'       => '_meta',
    ];
    

    public function verbs()
    {
        // Khai báo HTTP verbs cho từng action
        return [
            'index'   => ['GET','HEAD'],
            'view'    => ['GET','HEAD'],
            'create'  => ['POST'],
            'update'  => ['PUT','PATCH'],
            'delete'  => ['DELETE'],
            'options' => ['OPTIONS'],
        ];
    }

    public function actions()
    {
        // Dùng toàn bộ actions của ActiveController và gán scenario cho chúng
        $actions = parent::actions();

        // Áp dụng scenario để dùng đúng rules của User với action create và update
        $actions['create']['scenario'] = 'create';
        $actions['update']['scenario'] = 'update';

        // Tuỳ chỉnh lại action index để thêm phân trang và lọc theo các fields
        $actions['index']['prepareDataProvider'] = function () {
            $request = Yii::$app->request;
            $query = User::find();

            // Lọc các fields
            if ($u = $request->get('username')) {
                $query->andWhere(['like', 'username', $u]);
            }
            if ($e = $request->get('email')) {
                $query->andWhere(['like', 'email', $e]);
            }
            if (($s = $request->get('status')) !== null && $s !== '') {
                $query->andWhere(['status' => (int)$s]);
            }
            if ($r = $request->get('role')) {
                $query->andWhere(['role' => $r]);
            }

            $sort = new Sort([
                'attributes'    => ['id', 'username', 'email', 'role', 'status'],
                'defaultOrder'  => ['id' => SORT_DESC],
            ]);

            // Phân trang bằng page và per-page
            $page = max(1, (int)$request->get('page', 1));
            $perPage = min(100, max(1, (int)$request->get('per-page', 20)));

            // Sắp xếp mặc định là trường id -> nếu sau này có update sort thì sửa chỗ này
            $query->orderBy(['id' => SORT_DESC]);

            return new ActiveDataProvider([
                'query' => $query->orderBy($sort->orders),
                'sort'  => $sort,
                'pagination' => [
                    'page' => $page - 1,
                    'pageSize' => $perPage,
                ],
            ]);
        };

        return $actions;
    }
}