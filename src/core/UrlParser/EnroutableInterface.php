<?php


namespace Almacen\Core\UrlParser;


interface EnroutableInterface
{
    public function __construct(string $defaultNamespace, string $defaultController, string $defaultAction);

    public function parseUrl(string $url): ?Route;
}