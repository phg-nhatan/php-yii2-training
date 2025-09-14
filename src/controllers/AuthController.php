<?php
namespace app\controllers;

use Yii;
use app\models\forms\LoginForm;
use yii\rest\Controller;
use yii\web\Response;
use app\models\User;

class AuthController extends Controller
{
    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['admin/users']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['auth/login']);
    }

    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;

        $model = new User(['scenario' => 'create']);
        $model->role = 'user';
        $model->status = 1;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Registration successful you can login now');
                return $this->redirect(['auth/login']);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }
}