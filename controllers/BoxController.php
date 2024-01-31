<?php

namespace app\controllers;

use app\entities\Box\Box;
use app\models\BoxSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class BoxController extends Controller
{

    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex(): string
    {
        $searchModel = new BoxSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate(): string|Response
    {
        $model = new Box();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionChangeStatus($id, $status): bool
    {
        $model = $this->findModel($id);
        $model->scenario = Box::SCENARIO_CHANGE_STATUS;
        $model->status = $status;
        if (!$model->save()) {
            throw new ForbiddenHttpException();
        }

        return true;
    }

    public function actionUpdate($id): string|Response
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionChangeStatusBatch($ids, $status): void
    {
        Box::batchChangeStatus(explode(',', $ids), $status);
    }

    protected function findModel($id): Box
    {
        if (($model = Box::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
