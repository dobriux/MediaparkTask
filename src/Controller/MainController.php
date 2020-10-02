<?php

namespace App\Controller;

use App\Entity\HolidaySearch;
use App\Form\HolidaySearchType;
use App\Service\FetchHolidaysData;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param FetchHolidaysData $fetchHolidaysData
     * @param Request $request
     * @param SearchService $searchService
     * @return Response
     */
    public function index(FetchHolidaysData $fetchHolidaysData, Request $request, SearchService $searchService)
    {
        $searchForm = $this->createForm(HolidaySearchType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $formData = $searchForm->getData();
            $holidaySearchRepository = $this->getDoctrine()->getRepository(HolidaySearch::class);
            $holidayFromDatabase = $holidaySearchRepository ->findByYearAndCountry($formData->getCountry(), $formData->getYear());

            if($holidayFromDatabase){
                return $this->render('main/index.html.twig', [
                    'searchForm' => $searchForm->createView(),
                    'holidayFromDatabase' => $holidayFromDatabase,
                ]);
            }

            $holidayData = $fetchHolidaysData->getHolidaysForYear($formData->getYear(), $formData->getCountry());

            if(isset($holidayData["error"])){
                $this->addFlash('error', $holidayData["error"]);
                return $this->render('main/index.html.twig', [
                    'searchForm' => $searchForm->createView(),
                ]);
            }
            $searchService->searchResultsToDatabase($holidayData ,$formData->getCountry());
            $holidayFromDatabase = $holidaySearchRepository ->findByYearAndCountry($formData->getCountry(), $formData->getYear());

            return $this->render('main/index.html.twig', [
                'searchForm' => $searchForm->createView(),
                'holidayFromDatabase' => $holidayFromDatabase,
            ]);
        }
        return $this->render('main/index.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
