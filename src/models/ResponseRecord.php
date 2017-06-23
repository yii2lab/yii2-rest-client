<?php

namespace yii2module\rest_client\models;

/**
 * Class ResponseRecord
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class ResponseRecord
{
    /**
     * @var integer
     */
    public $status;
    /**
     * @var float
     */
    public $duration;
    /**
     * @var array
     */
    public $headers = [];
    /**
     * @var string
     */
    public $content;
}