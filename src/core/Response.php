<?php


namespace Almacen\Core;


class Response
{
    /** @var mixed */
    protected $content;

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function send()
    {
        echo $this->content;
    }
}