<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 4/27/18
 * Time: 6:37 PM
 */

namespace Contract;


interface Event
{
    const SUBSCRIBE = 'subscribe';
    const UN_SUBSCRIBE = 'unsubscribe';
    const SCAN = 'SCAN';
    const LOCATION = 'LOCATION';
    const CLICK = 'CLICK';
    const VIEW = 'VIEW';

}