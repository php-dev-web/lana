<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Enrollment;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    { 
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new User();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    { 
        $model = new User();



        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validatePassword($model->old_password)) {
                $model->password_hash = $model->setPassword($model->new_password);
                $model->save();
            } else {
                Yii::$app->session->addFlash('error', "Old password does not match");

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeStatus()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));

        if (Yii::$app->request->isAjax) {
            $model->status = Yii::$app->request->post('status');
            $model->save(false);
        }
    }

    public function actionReplenishBalance()
    {
        if (Yii::$app->request->post()) {
            $user_id = Yii::$app->request->post('User')['id'];
            $balance = Yii::$app->request->post('User')['balance'];

            $model = $this->findModel($user_id);
            $enrollmentModel = new Enrollment();

            $model->balance = $model->balance + $balance;
            $enrollmentModel->date = time();
            $enrollmentModel->user_id = $user_id;
            $enrollmentModel->sum = $balance;
            $enrollmentModel->action = 'Added';
            
            if ($enrollmentModel->save(false) && $model->save(false)) {
                Yii::$app->session->addFlash('success', "Balance changed successfully");
                return $this->redirect(['user/index']);
            }
        }
    }
}
