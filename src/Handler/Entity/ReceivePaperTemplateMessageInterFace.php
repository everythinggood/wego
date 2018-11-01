<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/13/18
 * Time: 11:44 AM
 */

namespace Handler\Entity;


use Contract\TemplateMessage;

class ReceivePaperTemplateMessageInterFace implements TemplateMessageInterFace
{
    /**
     * @var array
     */
    private $data;
    private $url = null;
    private $template_id = TemplateMessage::RECEIVE_PAPER;

    private $touser;

    /**
     * @param $touser
     */
    public function setTouser($touser){
        $this->touser = $touser;
    }

    public function setTitleField($content, $color = null)
    {
        $this->data['first']['value'] = $content;
        if ($color) $this->data['first']['color'] = $color;

    }

    public function setProductField($content, $color = null)
    {
        $this->data['keyword1']['value'] = $content;
        if ($color) $this->data['keyword1']['color'] = $color;
    }

    public function setTimeField($content = null, $color = null)
    {
        if ($content) $this->data['keyword2']['value'] = $content;
        else $this->data['keyword2']['value'] = (new \DateTime())->format('Y-m-d H:i:s');
        if ($color) $this->data['keyword1']['color'] = $color;
    }

    public function setRemarkField($content = null, $color = null)
    {
        if ($content) $this->data['remark']['value'] = $content;
        if ($color) $this->data['remark']['color'] = $color;
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function toArray()
    {
        return [
            'touser'=>$this->touser,
            'template_id'=>$this->template_id,
            'url' => $this->url,
            'data' => $this->data
        ];
    }
}