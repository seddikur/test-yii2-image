<?php

namespace app\modules\api\v1\controllers;

use yii\base\Action;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;

/**
 * Базовый контроллер для API
 * Class ApiController
 *
 * @property string $requestVersion
 */
class ApiController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
            'options' => ['OPTIONS'],
        ];
    }

}
