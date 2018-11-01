<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 7/3/18
 * Time: 11:23 AM
 */

namespace Service;


use Doctrine\ORM\EntityManager;
use Domain\AdBill;

class AdBIllService
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * BillService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * @param array $data
     * @return AdBill
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAdBillBy(array $data)
    {
        $adBill = new AdBill();

        $adBill = $this->autoSetAdBillBy($adBill, $data);

        $this->em->persist($adBill);
        $this->em->flush();

        return $adBill;
    }

    protected function autoSetAdBillBy(AdBill $adBill, array $data)
    {
        if ($data['wxOpenId']) $adBill->setWxOpenId($data['wxOpenId']);
        if ($data['adId']) $adBill->setWxOpenId($data['adId']);
        if ($data['placeId']) $adBill->setWxOpenId($data['placeId']);
        if ($data['machineCode']) $adBill->setWxOpenId($data['machineCode']);
        if ($data['brandId']) $adBill->setWxOpenId($data['brandId']);
        $dateTime = new \DateTime();
        $adBill->setCreateTime($dateTime);
        $adBill->setUpdateTime($dateTime);
        return $adBill;
    }

}