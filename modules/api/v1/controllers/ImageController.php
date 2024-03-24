<?php

namespace app\modules\api\v1\controllers;

use app\models\Images;
use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Swagger\Annotations as SWG;
use \app\modules\api\v1\controllers\ApiController;

//use OpenApi\Annotations as OA;

class ImageController extends ApiController
{
    public $modelClass = 'app\models\Images';

    /**
     * @return Response
     */
    /**
     * @SWG\SecurityScheme(
     *   securityDefinition="basicAuth",
     *   type="basic"
     * )
     * @SWG\Get(
     *     security={{"basicAuth":{}}},
     *     path="/user/login",
     *     summary="Получение токена",
     *     tags={"Пользователи"},
     *     description="Возвращает токен пользователя для Bearer авторизации",
     *
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Request not found"
     *     )
     * )
     */
    public function actionIndex()
    {
        $data = Images::find()->all();
        return $this->asJson($data);
    }

    /**
     * @param $id
     * @return Images
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $image = $this->findModel($id);
        return $this->asJson($image);
    }
    /**
     * Поиск картинки по id
     * @param $id
     * @return Images
     * @throws NotFoundHttpException
     */
    protected function findModel($id): Images
    {
        $model = Images::findOne(['id' => $id]);
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Картинка под номером ' . $id . ' не найдена.');
    }

    /**
     * @param $message
     * @return Response
     */
    private function errorResponse($message) {

        // set response code to 400
        \Yii::$app->response->statusCode = 400;

        return $this->asJson(['error' => $message]);
    }
}