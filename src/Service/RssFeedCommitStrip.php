<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RssFeedCommitStrip
{
    const IMAGE_CLASS = 'size-full';
    const IMAGE_EXTENSIONS = 'jpg|gif|png|JPG|GIF|PNG';
    private $rssFeedCommitStripClient;

    public function __construct(HttpClientInterface $rssFeedCommitStripClient)
    {
        $this->rssFeedCommitStripClient = $rssFeedCommitStripClient;
    }

    public function getListUrlNewsHasImages(): array
    {
        $articles = [];
        try {
            $response = $this->rssFeedCommitStripClient->request(
                'GET',
                '/en/feed/'
            );

            $data = simplexml_load_string($response->getContent(), 'SimpleXMLElement', LIBXML_NOCDATA);;

            foreach ($data->channel->item as $item) {
                $srcImage = $this->getImageSrc((string) $item->children("content", true));
                if (!empty($srcImage)) {
                    $articles[] = [
                        'url' => (string) $item->link,
                        'image' => $srcImage
                    ];
                }
            }

        } catch (\Exception $e) {

        }

        return $articles;
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