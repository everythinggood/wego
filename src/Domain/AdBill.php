<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 7/3/18
 * Time: 11:16 AM
 */

namespace Domain;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AdBill
 * @package Domain
 *
 * @ORM\Entity
 * @ORM\Table(name="ad_bill")
 */
class AdBill
{

    /**
     * @var string
     * @ORM\Column(name="wxopen_id",type="string")
     */
    protected $wxOpenId;
    /**
     * @var string
     * @ORM\Column(name="ad_id",type="string")
     */
    protected $adId;
    /**
     * @var string
     * @ORM\Column(name="place_id",type="string")
     */
    protected $placeId;
    /**
     * @var string
     * @ORM\Column(name="machine_code",type="string")
     */
    protected $machineCode;
    /**
     * @var string
     * @ORM\Column(name="brand_id",type="string")
     */
    protected $brandId;
    /**
     * @var \DateTime
     * @ORM\Column(name="create_time",type="datetime")
     */
    protected $createTime;
    /**
     * @var \DateTime
     * @ORM\Column(name="update_time",type="datetime")
     */
    protected $updateTime;

    /**
     * @return string
     */
    public function getWxOpenId(): string
    {
        return $this->wxOpenId;
    }

    /**
     * @param string $wxOpenId
     */
    public function setWxOpenId(string $wxOpenId)
    {
        $this->wxOpenId = $wxOpenId;
    }

    /**
     * @return string
     */
    public function getAdId(): string
    {
        return $this->adId;
    }

    /**
     * @param string $adId
     */
    public function setAdId(string $adId)
    {
        $this->adId = $adId;
    }

    /**
     * @return string
     */
    public function getPlaceId(): string
    {
        return $this->placeId;
    }

    /**
     * @param string $placeId
     */
    public function setPlaceId(string $placeId)
    {
        $this->placeId = $placeId;
    }

    /**
     * @return string
     */
    public function getMachineCode(): string
    {
        return $this->machineCode;
    }

    /**
     * @param string $machineCode
     */
    public function setMachineCode(string $machineCode)
    {
        $this->machineCode = $machineCode;
    }

    /**
     * @return string
     */
    public function getBrandId(): string
    {
        return $this->brandId;
    }

    /**
     * @param string $brandId
     */
    public function setBrandId(string $brandId)
    {
        $this->brandId = $brandId;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime(): \DateTime
    {
        return $this->createTime;
    }

    /**
     * @param \DateTime $createTime
     */
    public function setCreateTime(\DateTime $createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateTime(): \DateTime
    {
        return $this->updateTime;
    }

    /**
     * @param \DateTime $updateTime
     */
    public function setUpdateTime(\DateTime $updateTime)
    {
        $this->updateTime = $updateTime;
    }


}