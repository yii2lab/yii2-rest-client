<?php

namespace yii2lab\rest_client\formatters;

use yii\base\Object;
use yii\helpers\Html;

/**
 * Class RawFormatter
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class RawFormatter extends Object
{
    /**
     * @param \yii2lab\rest_client\models\ResponseRecord $record
     * @return string
     */
    public function format($record)
    {
        return Html::tag('pre',
            Html::tag('code',
                Html::encode($record->content),
                ['id' => 'response-content']
            )
        );
    }

    /**
     * @param \Exception $exception
     * @return string
     */
    protected function warn($exception)
    {
        return Html::tag('div', '<strong>Warning!</strong> ' . $exception->getMessage(), [
            'class' => 'alert alert-warning',
        ]);
    }
}