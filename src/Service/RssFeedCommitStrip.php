<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RssFeedCommitStrip
{
    const IMAGE_CLASS = 'size-full';
    const IMAGE_EXTENSIONS = 'jpg|gif|png|JPG|GIF|PNG';
    private $rssFeedCommitStripClient;
    private $logger;

    public function __construct(HttpClientInterface $rssFeedCommitStripClient, LoggerInterface $logger)
    {
        $this->rssFeedCommitStripClient = $rssFeedCommitStripClient;
        $this->logger = $logger;
    }

    public function getListUrlNewsHasImages(): array
    {
        try {
            $response = $this->rssFeedCommitStripClient->request(
                'GET',
                '/en/feed/'
            );

            $data = simplexml_load_string($response->getContent(), 'SimpleXMLElement', LIBXML_NOCDATA);;
            $articles = [];
            
            foreach ($data->channel->item as $item) {
                $srcImage = $this->getImageSrc((string) $item->children("content", true));
                if (!empty($srcImage)) {
                    $articles[] = [
                        'url' => (string) $item->link,
                        'image' => $srcImage
                    ];
                }
            }

            return $articles;

        } catch (\Exception $e) {
            $this->logger->error('Error fetching Rss Feed CommitStrip data', ['exception' => $e]);
            return [];
        }

    }

    function getImageSrc($htmlContent) :?string
    {
        $pattern = sprintf('/<img[^>]*class="[^"]*%s[^"]*"[^>]*src="([^"]*\.(%s))"/', preg_quote(self::IMAGE_CLASS, '/'), self::IMAGE_EXTENSIONS);

        if (preg_match($pattern, $htmlContent, $matches)) {
            return $matches[1];
        } else {
            return null;
        }
    }
}
