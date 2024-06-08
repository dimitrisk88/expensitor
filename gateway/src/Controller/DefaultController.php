<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DefaultController extends AbstractController
{
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    #[Route('/', name: 'app_default')]
    public function index(): JsonResponse
    {
        $cacheKey = 'test_cache_key';
        $cacheItem = $this->cache->getItem($cacheKey);
        dump($cacheItem, $_ENV);
        if (!$cacheItem->isHit()) {
            $data = 'This data is generated at ' . date('Y-m-d H:i:s');
            $cacheItem->set($data);
            $this->cache->save($cacheItem);
            $message = 'Cache miss. Data generated and stored in cache.';
        } else {
            $data = $cacheItem->get();
            $message = 'Cache hit. Data retrieved from cache.';
        }
        return $this->json([
            'message' => $message,
            'path' => 'src/Controller/DefaultController.php',
        ]);
    }
}