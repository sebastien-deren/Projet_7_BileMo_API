<?php

namespace App\Service\Headers;


use App\DTO\PaginationDto;
use Symfony\Component\HttpFoundation\JsonResponse;

interface PaginationHeaderInterface
{
    public function setHeaders(JsonResponse $response,PaginationDto $pagination,string $route):JsonResponse;
}
