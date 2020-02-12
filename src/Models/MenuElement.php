<?php


namespace Kata\Models;


class MenuElement
{
    public string $name;
    public string $href;
    public string $class;
    public string $linkClass;

    /**
     * MenuElement constructor.
     * @param string $name
     * @param string $href
     * @param string $class
     * @param string $linkClass
     */
    public function __construct(string $name, string $href, string $class = '', string $linkClass = '')
    {
        $this->name = $name;
        $this->href = $href;
        $this->class = $class;
        $this->linkClass = $linkClass;
    }
}