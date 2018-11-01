<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/15/18
 * Time: 7:07 PM
 */

namespace Handler;


use Contract\Other;
use Contract\RedisKey;
use GuzzleHttp\Client;
use Handler\Entity\User;
use Slim\Container;

class BackedHandler
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * PlaneHandler constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->client = $container[\Contract\Container::NAME_HTTP_CLIENT];
        $this->redis = $container[\Contract\Container::NAME_REDIS];
    }

    public function isOk($machine)
    {
        $status = $this->redis->hGet($machine, RedisKey::HASH_KEY_STATUS);
        if ($status == Other::STATUS_ONLINE) return true;
        return false;
    }

    /**
     * 判断今天是否有免费额度
     * @param User $user
     * @return bool
     */
    public function isFree(User $user)
    {

        $dateTime = new \DateTime();
        $wxOpenId = $user->openid;
        $key = RedisKey::KEY_AD_ . $dateTime->format('Y-m-d');
        if ($this->redis->hExists($key, $wxOpenId)) {
            return false;
        }

        return true;
    }

    /**
     * 一键关注页面
     * @param User $user
     * @param $machineCode
     * @return string
     */
    public function getAdQrCode(User $user, $machineCode)
    {

        $adQrCode = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQFL7zwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyeFBiU2NmanJkRWsxMGtLeTFyY1QAAgQURRpbAwQA6QcA';

        return $user && $machineCode ? $adQrCode : 'http://www.baidu.com';
    }

    /**
     * 显示二维码url
     * @param $machineCode
     * @param $sex
     * @return string
     */
    public function getAdID($machineCode, $sex)
    {

        $key = RedisKey::KEY_MACHINECODE_SEX_ . join('_', [$machineCode, $sex]);
        $adID = $this->redis->lIndex($key, 0);
        $this->filterAd($key,$adID);
        $adID = $this->redis->lIndex($key, 0);
        if ($adID) return $adID;
        return $this->redis->lIndex(RedisKey::KEY_AD_DEFAULT, 0);
    }

    protected function filterAd($key,$adId){
        $adInfo = $this->getAdInfo($adId);

        if ($adInfo[RedisKey::HASH_KEY_TYPE] == Other::TYPE_NUMBER && $adInfo[Other::TYPE_NUMBER] <= 0) {
            $this->redis->lPop($key);
        }

        if($adInfo[RedisKey::HASH_KEY_TYPE] == Other::TYPE_TIME && $adInfo[Other::TYPE_TIME] <= (new \DateTime())->format('Y-m-d')){
            $this->redis->lPop($key);
        }
    }

    public function getAdUrl($adId)
    {

        $key = RedisKey::KEY_AD_ . $adId;
        $adUrl = $this->redis->hGet($key, RedisKey::HASH_KEY_URL);
        return $adUrl;
    }

    /**
     * 保存用户扫码的机器码，以便在公众号找到对应的设备
     * @param $machineCodeAndAd
     * @param $wxOpenId
     * @return int
     */
    public function saveUserScan($machineCodeAndAd, $wxOpenId)
    {
        $dateTime = new \DateTime();
        $key = RedisKey::KEY_USER_SCAN_ . $dateTime->format('Y-m-d');
        return $this->redis->hSet($key, $wxOpenId, $machineCodeAndAd);
    }

    public function getMachineAndAdOnUserScan($wxOpenId)
    {
        $dateTime = new \DateTime();
        $key = RedisKey::KEY_USER_SCAN_ . $dateTime->format('Y-m-d');
        return $this->redis->hGet($key, $wxOpenId);
    }

    public function getMachineInfo($machineCode)
    {
        $key = RedisKey::KEY_MACHINECODE_ . $machineCode;
        return $this->redis->hGetAll($key);
    }

    public function getAdInfo($adId)
    {
        $key = RedisKey::KEY_AD_ . $adId;
        return $this->redis->hGetAll($key);
    }

    public function resetAdOnNumber($adId){
        $adInfo = $this->getAdInfo($adId);

        if($adInfo['type']=='number'){
            $this->redis->hSet(RedisKey::KEY_AD_.$adId,Other::TYPE_NUMBER,$adInfo[Other::TYPE_NUMBER]-1);
        }
    }

    public function sendPaper($machineCode)
    {

        //...

        return true;
    }
}