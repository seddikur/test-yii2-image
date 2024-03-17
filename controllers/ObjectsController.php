<?php

namespace app\controllers;

use app\models\Images;
use app\models\Objects;
use app\models\ObjectsSearch;
use vova07\imperavi\actions\UploadFileAction;
use yii\base\InvalidArgumentException;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use Yii;
use Exception;

/**
 * ObjectsController implements the CRUD actions for Objects model.
 */
class ObjectsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     * @throws InvalidArgumentException
     */
    public function actions(): array
    {
        return [
            // Загрузка изображений из редактора
//            'image-upload' => [
//                'class' => UploadFileAction::class,
//                'url' => '/uploads/catalog_description/',
//                'path' => Yii::getAlias('@backend/web/uploads/catalog_description'),
//                'replace' => true
//            ],
//            'file-upload' => [
//                'class' => UploadFileAction::class,
//                'url' => '/uploads/catalog_description/',
//                'path' => Yii::getAlias('@backend/web/uploads/catalog_description'),
//                'uploadOnlyImage' => false,
//            ],
        ];

    }

    /**
     * Lists all Objects models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ObjectsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Objects model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Objects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Objects();
        $upload = new Images();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            VarDumper::dump($upload->validate(),10, true); die();
            if ($upload->load(\Yii::$app->request->post()) && $upload->validate()) {
                try {
                    //Сохранить загруженное изображение по ссылке
                    $imgUrlStr = !empty(Yii::$app->request->post('current_img_url')) ?
                        Yii::$app->request->post('current_img_url') :
                        Yii::$app->request->post('img_url');
                    if ($imgUrlStr) {
                        $imgUrls = explode(';,;', $imgUrlStr);
                        $upload->loadImagesByUrl($imgUrls);
                    } else {
                        //Сохранить загруженное изображение при добалении
                        $upload->loadImages(UploadedFile::getInstances($model, 'imageFile'));
                        //запись название файла в базу
//                    $model->image =$model->imageFile;
                    }

                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
            }
            \Yii::$app->session->setFlash('success', 'Картинка успешно загружена');
            return $this->redirect(['index']);

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'upload' => $upload,
        ]);
    }

    /**
     * Updates an existing Objects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Objects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Objects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Objects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Objects::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
