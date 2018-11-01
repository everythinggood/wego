<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/16/18
 * Time: 12:00 PM
 */

namespace Handler;


use Contract\Container;
use Contract\ENV;
use Endroid\QrCode\QrCode;
use Psr\Container\ContainerInterface;

class QrCodeHandler
{
    /**
     * @var array
     */
    private $setting;

    /**
     * QrCodeHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setting = $container[Container::NAME_SETTING]['wx'];
    }

    public function getQrCodeByMachineCode(String $machineCode):QrCode
    {
        $url = sprintf($this->setting[ENV::ENV_MACHINE_SCAN_URL].'?machineCode=%s',$machineCode);
        $qrCode = new QrCode($url);

        return $qrCode;
    }
}