<?php

namespace app\controllers;

use app\models\Images;
use Symfony\Component\Filesystem\Filesystem;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use yii\web\Response;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile;
use Yii;
use Exception;

/**
 * ImagesController implements the CRUD actions for Images model.
 */
class ImagesController extends Controller
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
//                'timestamp' => [
//                    'class' => 'yii\behaviors\TimestampBehavior',
//                    'attributes' => [
//                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
//                    ],
//                ],
            ]

        );
    }

    /**
     * Lists all Images models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Images::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Images model.
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
     * Creates a new Images model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Images();

        if ($this->request->isPost) {

            if ($model->load($this->request->post()) && $model->validate()) {
                try {
                    //Сохранить загруженное изображение по ссылке
                    $imgUrlStr = !empty(Yii::$app->request->post('current_img_url')) ?
                        Yii::$app->request->post('current_img_url') :
                        Yii::$app->request->post('img_url');
                    if ($imgUrlStr) {
                        $imgUrls = explode(';,;', $imgUrlStr);
                        $model->loadImagesByUrl($imgUrls);
                    } else {
                        //Сохранить загруженное изображение при добалении
                        $model->loadImages(UploadedFile::getInstances($model, 'imageFile'));
                    }
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
                \Yii::$app->session->setFlash('success', 'Картинка успешно загружена');
                return $this->redirect(['index']);
            }
        }  else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Images model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) ) {

                try {
                    //Сохранить загруженное изображение по ссылке
                    $imgUrlStr = !empty(Yii::$app->request->post('current_img_url')) ?
                        Yii::$app->request->post('current_img_url') :
                        Yii::$app->request->post('img_url');
                    if ($imgUrlStr) {
                        $imgUrls = explode(';,;', $imgUrlStr);
                        $model->loadImagesByUrl($imgUrls);
                    } else {
                        //Сохранить загруженное изображение при добалении
                        $model->loadImages(UploadedFile::getInstances(new Images(), 'images'));
                    }

                } catch (Exception $e) {
                    echo \yii\helpers\Json::encode($model->getErrors());
                    die();
                }
             \Yii::$app->session->setFlash('success', 'Картинка успешно загружена');
                return $this->redirect(['index']);
            }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Удалить изображение.
     * @param int $imageId
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws StaleObjectException
     */
    public function actionRemoveImage(int $imageId): array
    {
        /** @var Request $request */
        $request = Yii::$app->request;

        // Находим изображение по его ID
        $image = Images::findOne($imageId);
        if (!$image) {
            throw new UnprocessableEntityHttpException("Изображение с идентификатором {$imageId} не найдено.");
        }


        if (!empty($image->filename)) {
            /** @var string $imagePath Абсолютный путь до изображения */
            $imagePath = Yii::getAlias($image->getFullPath());
            $thumbPath = Yii::getAlias($image->getFullPathThumb());

        } else {
            /** @var string $imagePath Абсолютный путь до изображения */
            $imagePath = Yii::getAlias("@webroot/{$image->img_url}");
        }

        $fs = new Filesystem();

        $isDeleted = false;
        if ($fs->exists($imagePath)) {
            /** Удаляем изображение из БД */
            if ($image->delete()) {

                if (!empty($imagePath) && $fs->exists($imagePath)) {
                    // удаление файла
                    $fs->remove($imagePath);
                }

                if (!empty($thumbPath) && $fs->exists($thumbPath)) {
                    // удаление миниатюры
                    $fs->remove($thumbPath);
                }

                $isDeleted = true;
            }
        } else {
            $image->delete();
            $isDeleted = true;
        }
        //        \Yii::$app->noty->success("Изображение удалено");

        if ($isDeleted) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => 'deleted',
            ];
        }
        throw new UnprocessableEntityHttpException('error');

    }

    /**
     * Deletes an existing Images model.
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
     * Finds the Images model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Images the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Images::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
