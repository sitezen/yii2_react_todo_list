<?php

namespace app\behaviors;

/**
 * Created by PhpStorm.
 * User: stnik
 * Date: 21.08.2018
 * Time: 11:40
 */


use yii;
use yii\base\Behavior;


class Metatags extends Behavior
{
    public function events()
    {
        return [
            yii\web\Controller::EVENT_BEFORE_ACTION => 'getTags'
        ];
    }

    public function getTags()
    {
        yii::$app->params['metatags'] = $this->getTagsByRoute(Yii::$app->controller->route);
    }


    /**
     * @param $route
     * @return array
     */
    protected function getTagsByRoute($route)
    {
        $metaTage = [];
        // ...
        return $metaTage;
    }

}