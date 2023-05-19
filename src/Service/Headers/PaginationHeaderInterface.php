<?php

namespace App\Service\Headers;



use App\DTO\PaginationDto;
use Hateoas\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\JsonResponse;

interface PaginationHeaderInterface
{
    public function setHeaders(JsonResponse $response,PaginationDto $pagination,string $route):JsonResponse;
}
