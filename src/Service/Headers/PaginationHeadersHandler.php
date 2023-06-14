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

    public function setHeaders(JsonResponse $response, PaginationDto $pagination,string $route, array $requiredRouteArgument=[]): JsonResponse
    {
        $page = $pagination->page;
        $limit = $pagination->limit;
        $lastPage = '<' . $this->routeGeneration($route, $pagination->maxPage, $limit,$requiredRouteArgument) . '>; rel="last", ';
        $firstPage = '<' . $this->routeGeneration($route, 1, $limit,$requiredRouteArgument) . '>; rel="first"';
        $prev = $page === 1 ? "" : '<' . $this->routeGeneration($route, $page - 1, $limit,$requiredRouteArgument) . '>; rel="prev", ';
        $next = $page === $pagination->maxPage ? "" : '<' . $this->routeGeneration($route, $page + 1, $limit,$requiredRouteArgument) . '>; rel="next", ';

        $response->headers->set("link", $prev . $next . $lastPage . $firstPage);
        return $response;
    }

    private function routeGeneration(string $route, int $page, int $limit,array $routeArgument): string
    {

        $routeArgument["page"]=$page;
        $routeArgument["limit"]=$limit;
        return $this->urlGenerator->generate($route, $routeArgument, urlGeneratorInterface::ABSOLUTE_URL);
    }
}
