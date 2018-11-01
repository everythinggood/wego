<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 4/24/18
 * Time: 10:47 AM
 */

namespace Contract;


interface Container
{

    const NAME_VIEW = 'view';

    const NAME_LOGGER = 'logger';

    const NAME_SETTING = 'setting';

    const NAME_HTTP_CLIENT = 'http-client';

    const NAME_HANDLER_WX_JS = 'wx-js-handler';

    const NAME_HANDLER_BACKED = 'backed-handler';

    const NAME_WX_APP = 'wx-app';

    const NAME_WX_PAYMENT = 'wx-payment';

    const NAME_JSON_VIEW = 'json-view';

    const NAME_SESSION = 'session';

    const NAME_REDIS = 'redis';

}