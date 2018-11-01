<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/7/18
 * Time: 4:03 PM
 */

namespace Action\Test;


use Contract\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class TestViewAction
{
    /**
     * @var \Slim\Container
     */
    private $container;
    /**
     * @var Twig
     */
    private $view;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container[Container::NAME_VIEW];
    }


    public function adCode(ServerRequestInterface $request,ResponseInterface $response,array $args){

        return $this->view->render($response,'/test/adCode.phtml');

    }

    public function offLine(ServerRequestInterface $request,ResponseInterface $response,array $args){

        return $this->view->render($response,'/test/offLine.phtml');

    }

    public function fail(ServerRequestInterface $request,ResponseInterface $response,array $args){

        return $this->view->render($response,'/test/fail.phtml');

    }

    public function success(ServerRequestInterface $request,ResponseInterface $response,array $args){

        return $this->view->render($response,'/test/success.phtml');

    }


}