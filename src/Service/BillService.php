<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/23/18
 * Time: 11:31 AM
 */

namespace Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Domain\Bill;

class BillService
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
     * @param Bill $bill
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createBill(Bill $bill){
        $this->em->flush($bill);
    }

    public function findByPrePayId($prepayId){
        return $this->em->getRepository(Bill::class)->findBy(['prepay_id'=>$prepayId]);
    }

    public function generateBillNo(){
        return (new \DateTime('now'))->format('yyyymmddhhiiss').rand(0,10);
    }

    /**
     * @param $billNo
     * @param $prepayId
     * @param $openid
     * @param $totalFee
     * @return Bill
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createBillBy($billNo, $prepayId,$openid,$totalFee){
        $bill = new Bill();
        $bill->setBillNo($billNo);
        $bill->setPrepayId($prepayId);
        $bill->setStatus(Bill::STATUS_PREPAY);
        $bill->setWxOpenid($openid);
        $bill->setTotalFee($totalFee);
        $bill->setCreateTime(new \DateTime('now'));

        $this->em->flush($bill);

        return $bill;
    }

    public function findBillBy($billNo,$status=Bill::STATUS_PREPAY){
        return $this->em->getRepository(Bill::class)->findOneBy([
            'bill_no'=>$billNo,
            'status'=>$status
        ]);
    }

    /**
     * @param Bill $bill
     * @param array $data
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveBillBy(Bill $bill, array $data){

        if (isset($data['status'])) $bill->setStatus($data['status']);
        if (isset($data['wxPayNo'])) $bill->setWxPayNo($data['wxPayNo']);

        $this->em->persist($bill);
        $this->em->flush();
    }
}