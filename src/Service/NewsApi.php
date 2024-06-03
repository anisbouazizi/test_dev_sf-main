<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsApi
{
    private $newsApiClient;
    private $logger;

    public function __construct(HttpClientInterface $newsApiClient, LoggerInterface $logger)
    {
        $this->newsApiClient = $newsApiClient;
        $this->logger = $logger;
    }

    public function getListUrlNewsHasImages(): array
    {
        try {
            $response = $this->newsApiClient->request(
                'GET',
                '/v2/top-headlines', [
                'query' => [
                    'country' => 'us',
                    'apiKey' => 'c782db1cd730403f88a544b75dc2d7a0',
                ],
            ]);

            $data = json_decode($response->getContent(), true);
            $articles = [];

            foreach ($data['articles'] as $article) {
                if (!empty($article['urlToImage'])) {
                    $articles[] = [
                        'url' => $article['url'],
                        'image' => $article['urlToImage']
                    ];
                }
            }

            return $articles;

        } catch (\Exception $e) {
            $this->logger->error('Error fetching news API data', ['exception' => $e]);
            return [];
        }

    }
}