<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/23/18
 * Time: 10:38 AM
 */

namespace Domain;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Bill
 * @package Domain
 *
 * @ORM\Entity()
 * @ORM\Table(name="bill")
 */
class Bill
{

    const STATUS_PREPAY = 'prepay';
    const STATUS_PAID = 'paid';
    const STATUS_CANCEL = 'cancel';
    const STATUS_REFUND_PENDING = 'refund_pending';
    const STATUS_REFUND = 'refund';

    static $statusMapping = [
        self::STATUS_PREPAY=>'待支付',
        self::STATUS_PAID=>'已支付',
        self::STATUS_CANCEL=>'已取消',
        self::STATUS_REFUND_PENDING=>'退款中',
        self::STATUS_REFUND=>'已退款'
    ];

    /**
    @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $bill_no;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $prepay_id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $wx_pay_no;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $wx_openid;
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $total_fee;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    protected $create_time;
    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    protected $update_time;

    /**
     * @return string
     */
    public function getBillNo(): string
    {
        return $this->bill_no;
    }

    /**
     * @param string $bill_no
     */
    public function setBillNo(string $bill_no): void
    {
        $this->bill_no = $bill_no;
    }

    /**
     * @return string
     */
    public function getPrepayId(): string
    {
        return $this->prepay_id;
    }

    /**
     * @param string $prepay_id
     */
    public function setPrepayId(string $prepay_id): void
    {
        $this->prepay_id = $prepay_id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @param mixed $create_time
     */
    public function setCreateTime($create_time): void
    {
        $this->create_time = $create_time;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    public function setUpdateTime(): void
    {
        $this->update_time = new \DateTime('now');
    }

    /**
     * @return string
     */
    public function getWxOpenid(): string
    {
        return $this->wx_openid;
    }

    /**
     * @param string $wx_openid
     */
    public function setWxOpenid(string $wx_openid): void
    {
        $this->wx_openid = $wx_openid;
    }

    /**
     * @return float
     */
    public function getTotalFee(): float
    {
        return $this->total_fee;
    }

    /**
     * @param float $total_fee
     */
    public function setTotalFee(float $total_fee): void
    {
        $this->total_fee = $total_fee;
    }

    /**
     * @return string
     */
    public function getWxPayNo(): string
    {
        return $this->wx_pay_no;
    }

    /**
     * @param string $wx_pay_no
     */
    public function setWxPayNo(string $wx_pay_no)
    {
        $this->wx_pay_no = $wx_pay_no;
    }

}