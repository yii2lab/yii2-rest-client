<?php

namespace yii2module\rest_client\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii2module\rest_client\helpers\ArrayHelper;
use yii2module\rest_client\models\RequestEvent;
use yii2module\rest_client\models\RequestForm;
use yii2module\rest_client\models\ResponseEvent;
use yii2module\rest_client\models\ResponseRecord;
use yii2module\rest_client\Module;
use yii2module\rest_client\helpers\Authorization;

/**
 * Class RequestController
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class RequestController extends Controller
{
    /**
     * @var \yii2module\rest_client\Module
     */
    public $module;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'create';

    public function actionCreate($tag = null)
    {
        /** @var RequestForm $model */
        $model = Yii::createObject(RequestForm::className());
        $record = new ResponseRecord();

        if (
            $tag !== null &&
            !$this->module->storage->load($tag, $model, $record)
        ) {
            throw new NotFoundHttpException('Request not found.');
        }

        if (
            $model->load(Yii::$app->request->post()) &&
            $model->validate()
        ) {
            $record = $this->send($model);
            $tag = $this->module->storage->save($model, $record);

            //return $this->redirect(['create', 'tag' => $tag, '#' => 'response']);
        }

        $model->addEmptyRows();
        $history = $this->module->storage->getHistory();
        $collection = $this->module->storage->getCollection();

        foreach ($history as $_tag => &$item) {
            $item['in_collection'] = isset($collection[$_tag]);
        }
        unset($item);
        // TODO Grouping will move to the config level
        $collection = ArrayHelper::group($collection, function ($row) {
            if (preg_match('|[^/]+|', ltrim($row['endpoint'], '/'), $m)) {
                return $m[0];
            } else {
                return 'common';
            }
        });

        return $this->render('create', [
            'tag' => $tag,
            'baseUrl' => rtrim($this->module->baseUrl, '/') . '/',
            'model' => $model,
            'record' => $record,
            'history' => $history,
            'collection' => $collection,
        ]);
    }

    /**
     * @param RequestForm $model
     * @return ResponseRecord
     */
    protected function send(RequestForm $model)
    {
        $this->module->trigger(Module::EVENT_ON_REQUEST, new RequestEvent([
            'form' => $model,
        ]));

        $record = Authorization::sendRequest($model);

        $this->module->trigger(Module::EVENT_ON_RESPONSE, new ResponseEvent([
            'form' => $model,
            'record' => $record,
        ]));

        return $record;
    }
}