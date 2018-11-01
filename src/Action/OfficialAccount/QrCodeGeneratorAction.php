<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/16/18
 * Time: 12:05 PM
 */

namespace Action\OfficialAccount;


use Action\ActionInterface;
use Handler\QrCodeHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;

class QrCodeGeneratorAction implements ActionInterface
{
    /**
     * @var QrCodeHandler
     */
    private $qrCodeHandler;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->qrCodeHandler = new QrCodeHandler($container);
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
        /** @var Request $request */
        $machineCode = $request->getParam('machineCode');

        if(!$machineCode) throw new \Exception("require machineCode parameter");

        $qrCode = $this->qrCodeHandler->getQrCodeByMachineCode($machineCode);

        $response = $response->withHeader("Content-type",$qrCode->getContentType());

        $response->getBody()->write($qrCode->writeString());

        return $response;

    }
}