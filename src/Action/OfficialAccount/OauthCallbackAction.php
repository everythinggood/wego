<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/12/18
 * Time: 7:41 PM
 */

namespace Action\OfficialAccount;


use Action\ActionInterface;
use Contract\Container;
use Contract\Session;
use EasyWeChat\OfficialAccount\Application;
use Monolog\Logger;
use Overtrue\Socialite\User;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use SlimSession\Helper;

class OauthCallbackAction implements ActionInterface
{

    /**
     * @var Application
     */
    private $app;
    /**
     * @var Helper
     */
    private $sHelper;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * OauthCallbackAction constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->app = $container[Container::NAME_WX_APP];
        $this->sHelper = $container[Container::NAME_SESSION];
        $this->router = $container->get('router');
        $this->logger = $container[Container::NAME_LOGGER];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->logger->addInfo("/wx/oauthCallback");

        /** @var Request $request */
        $state = $request->getParam('state');

        if(!empty($this->sHelper->get(Session::NAME_USER_INFO))){
            /** @var Response $response */
            $targetUrl = $this->router->pathFor("wx_index", [], ['machineCode' => $state]);
            $this->logger->addInfo("oauth callback redirect to $targetUrl");
            return $response->withRedirect($targetUrl);
        }

        /** @var User $user */
        $user = $this->app->oauth->user();

        if (!$user) throw new \Exception("oauth callback can not get user!");

        $this->logger->addInfo("oauth callback set user info to session", $user->toArray());

        $this->sHelper->set(Session::NAME_USER_INFO, $user->toArray());

        /** @var Response $response */
        $targetUrl = $this->router->pathFor("wx_index", [], ['machineCode' => $state]);
        $this->logger->addInfo("oauth callback redirect to $targetUrl");
        return $response->withRedirect($targetUrl);

    }
}