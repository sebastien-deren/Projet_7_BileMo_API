<?php

namespace App\Service\Headers;


use App\DTO\PaginationDto;
use Symfony\Component\HttpFoundation\JsonResponse;

interface PaginationHeaderInterface
{
    /**
     * @param JsonResponse $response
     * @param PaginationDto $pagination
     * @param string $route
     * @param array $requiredRouteArgument
     * @return JsonResponse
     */
    public function setHeaders(JsonResponse $response,PaginationDto $pagination,string $route,array $requiredRouteArgument = []):JsonResponse;
}
