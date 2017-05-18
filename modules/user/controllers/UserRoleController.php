<?php

namespace maximkozhin\user\modules\user\controllers;

use maximkozhin\user\models\User;
use Yii;
use maximkozhin\user\models\UserRole;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserRoleController implements the CRUD actions for UserRole model.
 */
class UserRoleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserRole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => UserRole::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserRole model.
     * @param integer $user_id
     * @param string $alias
     * @return mixed
     */
    public function actionView($user_id, $alias)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $alias),
        ]);
    }

    /**
     * Creates a new UserRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserRole();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if( ($userRole = UserRole::findOne(['user_id' => $model->user_id, 'alias' => $model->alias])) === null) {
                $userRole = new UserRole(['user_id' => $model->user_id,'alias' => $model->alias]);
            }
            $userRole->save(false);
            return $this->redirect(['view', 'user_id' => $model->user_id, 'alias' => $model->alias]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param string $alias
     * @return mixed
     */
    public function actionUpdate($user_id, $alias)
    {
        $model = $this->findModel($user_id, $alias);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if( ($userRole = UserRole::findOne(['user_id' => $model->user_id, 'alias' => $model->alias])) === null) {
                $userRole = new UserRole(['user_id' => $model->user_id,'alias' => $model->alias]);
            }
            $userRole->save(false);
            return $this->redirect(['view', 'user_id' => $model->user_id, 'alias' => $model->alias]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UserRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param string $alias
     * @return mixed
     */
    public function actionDelete($user_id, $alias)
    {
        $this->findModel($user_id, $alias)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param string $alias
     * @return UserRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $alias)
    {
        if (($model = UserRole::findOne(['user_id' => $user_id, 'alias' => $alias])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
