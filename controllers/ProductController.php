<?php

namespace app\controllers;

use app\entities\Box\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class ProductController extends Controller
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

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($boxId): string|Response
    {
        $model = new Product();
        $model->box_id = $boxId;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['box/view', 'id' => $model->box_id]);
            }
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id, $boxId): string|Response
    {
        $model = $this->findModel($id);
        $model->box_id = $boxId;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['box/view', 'id' => $model->box_id]);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id): Response
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['box/view', 'id' => $model->box_id]);
    }

    protected function findModel($id): Product
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
