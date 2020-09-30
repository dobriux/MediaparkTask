<?php

namespace App\Controller;

use App\Form\HolidaySearchType;
use App\Service\FetchHolidaysData;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param FetchHolidaysData $fetchHolidaysData
     * @return Response
     */
    public function index(FetchHolidaysData $fetchHolidaysData)
    {
        $supportedCountries = $fetchHolidaysData->getSupportedCountries();
        $searchForm = $this->createForm(HolidaySearchType::class);

        return $this->render('main/index.html.twig', [
            'supportedCountries' => $supportedCountries,
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
