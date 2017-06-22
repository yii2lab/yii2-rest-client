<?php

namespace yii2lab\rest_client\models;

use yii\base\Event;

/**
 * Class RequestEvent
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class RequestEvent extends Event
{
    /**
     * @var RequestForm
     */
    public $form;
}