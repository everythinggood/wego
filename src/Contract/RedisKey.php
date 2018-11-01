<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 7/3/18
 * Time: 9:32 AM
 */

namespace Contract;


interface RedisKey
{
    const KEY_MACHINECODE_ = 'machineCode_';
    const KEY_AD_ = 'ad_';
    const KEY_MACHINECODE_SEX_ = 'machineCode_sex_';
    const KEY_AD_DEFAULT = 'ad_default';
    const KEY_USER_SCAN_ = 'user_scan_';
    const HASH_KEY_STATUS = 'status';
    const HASH_KEY_PLACE_ID = 'place_id';
    const HASH_KEY_BRAND_ID = 'brand_id';
    const HASH_KEY_TYPE = 'type';
    const HASH_KEY_URL = 'url';
}