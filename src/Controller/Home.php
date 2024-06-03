<?php

namespace App\Controller;


use App\Service\NewsApi;
use App\Service\RssFeedCommitStrip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\ArrayUtils;

class Home extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param NewsApi $newsApi
     * @param RssFeedCommitStrip $rssFeedCommitStrip
     * @return Response
     */
    public function __invoke(NewsApi $newsApi, RssFeedCommitStrip $rssFeedCommitStrip): Response
    {
        $newsUrls = $newsApi->getListUrlNewsHasImages();
        $rssFeedUrls = $rssFeedCommitStrip->getListUrlNewsHasImages();

        $articles = ArrayUtils::deduplicateMultidimensionalArraysByKey('url', $newsUrls, $rssFeedUrls);

        return $this->render('default/index.html.twig', array('articles' => $articles));
    }
}