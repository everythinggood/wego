<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/13/18
 * Time: 11:42 AM
 */

namespace Handler\Entity;


interface TemplateMessageInterFace
{

    /**
     * @return array
     */
    public function toArray();
    public function setTouser($touser);

}