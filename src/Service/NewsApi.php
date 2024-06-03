<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsApi
{
    private $newsApiClient;

    public function __construct(HttpClientInterface $newsApiClient)
    {
        $this->newsApiClient = $newsApiClient;
    }

    public function getListUrlNewsHasImages(): array
    {
        $articles = [];
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

            foreach ($data['articles'] as $article) {
                if (!empty($article['urlToImage'])) {
                    $articles[] = [
                        'url' => $article['url'],
                        'image' => $article['urlToImage']
                    ];
                }
            }

        } catch (\Exception $e) {

        }

        return $articles;
    }
}