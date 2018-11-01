<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/15/18
 * Time: 5:40 PM
 */

namespace Handler;


use Contract\ENV;
use GuzzleHttp\Client;
use Monolog\Logger;
use Slim\Container;

class WxJsHandler
{

    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var array
     */
    private $setting;
    /**
     * @var Client
     */
    private $client;

    /**
     * WxJsHandler constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->logger = $container[\Contract\Container::NAME_LOGGER];
        $this->setting = $container[\Contract\Container::NAME_SETTING]['wx'];
        $this->client = $container[\Contract\Container::NAME_HTTP_CLIENT];
    }

    public function getSnsApiBaseUrl(String $state = '123')
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect";

        $url = sprintf($url, $this->setting[ENV::ENV_APP_ID], urlencode($this->setting[ENV::ENV_REDIRECT_URL]), $state);

        $this->logger->addInfo("genera snsapi_base Url: [$url]");

        return $url;

    }

    public function getSnsApiUserInfoUrl(String $state = '123')
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s#wechat_redirect';

        $url = sprintf($url, $this->setting[ENV::ENV_APP_ID], urlencode($this->setting[ENV::ENV_REDIRECT_URL]), $state);

        $this->logger->addInfo("genera snsapi_userinfo Url: [$url]");

        return;
    }

    /**
     *
     * { "access_token":"ACCESS_TOKEN",
     * "expires_in":7200,
     * "refresh_token":"REFRESH_TOKEN",
     * "openid":"OPENID",
     * "scope":"SCOPE" }
     * @param $code
     * @return mixed
     */
    public function getOpenidByCode($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';

        $url = sprintf($url, $this->setting[ENV::ENV_APP_ID], $this->setting[ENV::ENV_APP_SECRET], $code);

        $this->logger->addInfo("get openId by code: [$url]");

        $response = $this->client->get($url);

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $this->logger->addInfo("response on weixin.qq.com:", $json);

        return array_key_exists('openid', $json) ? $json['openid'] : null;

    }

    /**
     * @throws \Exception
     */
    public function getAccessTokenByFile()
    {
        if (!file_exists(__DIR__ . '/../../cache/access_token')) {
            throw new \Exception('access_token is not found!');
        }

        $access_token = file_get_contents(__DIR__ . '/../../cache/access_token');

        return $access_token;
    }

    /**
     *
     * {    "openid":" OPENID",
     * " nickname": NICKNAME,
     * "sex":"1",
     * "province":"PROVINCE"
     * "city":"CITY",
     * "country":"COUNTRY",
     * "headimgurl":    "http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
     * "privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
     * "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
     * }
     * @param String $accessToken
     * @param $openId
     * @return array | null
     */
    public function getUserInfo(String $accessToken, $openId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN';

        $url = sprintf($url, $accessToken, $openId);

        $this->logger->addInfo("get userInfo on weixin.qq.com: [$url]");

        $response = $this->client->get($url);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->logger->addInfo('response on weixin.qq.com: ', $json);

        return array_key_exists('sex', $json) ? $json : null;
    }

    public function getFollowUrl($biz = 'MzU2MzU5MjY1OQ==', $scene = 110)
    {
        $url = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=%s&scene=%s#wechat_redirect";

        $url = sprintf($url, $biz, $scene);

        return $url;
    }


}