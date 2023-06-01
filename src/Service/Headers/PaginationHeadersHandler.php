<?php

namespace App\Service\Headers;

use App\DTO\PaginationDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginationHeadersHandler implements PaginationHeaderInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function setHeaders(JsonResponse $response, PaginationDto $pagination,string $route): JsonResponse
    {
        $page = $pagination->page;
        $limit = $pagination->limit;
        $lastPage = '<' . $this->routeGeneration($route, $pagination->maxPage, $limit) . '>; rel="last", ';
        $firstPage = '<' . $this->routeGeneration($route, 1, $limit) . '>; rel="first"';
        $prev = $page === 1 ? "" : '<' . $this->routeGeneration($route, $page - 1, $limit) . '>; rel="prev", ';
        $next = $page === $lastPage ? "" : '<' . $this->routeGeneration($route, $page + 1, $limit) . '>; rel="next", ';

        $response->headers->set("link", $prev . $next . $lastPage . $firstPage);
        return $response;
    }

    private function routeGeneration(string $route, int $page, int $limit): string
    {
        return $this->urlGenerator->generate($route, ["page" => $page, "limit" => $limit], urlGeneratorInterface::ABSOLUTE_URL);
    }
}
