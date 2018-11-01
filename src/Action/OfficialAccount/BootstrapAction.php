<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/17/18
 * Time: 10:51 AM
 */

namespace Action\OfficialAccount;


use Action\ActionInterface;
use Contract\Container;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\OfficialAccount\Application;
use Handler\WX\EventMessageHandler;
use Handler\WX\TextMessageHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class BootstrapAction implements ActionInterface
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->app = $container[Container::NAME_WX_APP];
        $this->logger = $container[Container::NAME_LOGGER];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @throws
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var Request $request */
        $this->logger->addInfo('wx/bootstrap', $request->getParams());

        $this->app->server->push(TextMessageHandler::class, Message::TEXT);
        $this->app->server->push(EventMessageHandler::class, Message::EVENT);

        $syResponse = $this->app->server->serve();

        $factory = new DiactorosFactory();
        return $factory->createResponse($syResponse);

    }

}