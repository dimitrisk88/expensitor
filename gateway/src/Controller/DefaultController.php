<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DefaultController extends AbstractController
{
    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $httpClient,
    ) {
    }

    #[Route('/', name: 'app_default')]
    public function index(): JsonResponse
    {
        $cacheKey = 'microservice_cache_key';
        // $this->cache->delete($cacheKey);
        $cacheItem = $this->cache->getItem($cacheKey);
        if (!$cacheItem->isHit()) {
            $response = $this->httpClient->request('GET', 'http://users_server/');
            $userData = $response->toArray();
            $userData['data'] = 'This data is generated at ' . date('Y-m-d H:i:s');
            $cacheItem->set($userData);
            $this->cache->save($cacheItem);
            $message = 'Cache miss. Data generated and stored in cache.';
        } else {
            $userData = $cacheItem->get();
            $message = 'Cache hit. Data retrieved from cache.';
        }
        return $this->json([
            'userData' => $userData,
            'message' => $message,
        ]);
    }
}
