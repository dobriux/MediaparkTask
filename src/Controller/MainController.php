<?php

namespace App\Controller;

use App\Entity\HolidaySearch;
use App\Form\HolidaySearchType;
use App\Service\FetchHolidaysData;
use DateTime;
use Exception;
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
     * @return Response
     * @throws Exception
     */
    public function index(FetchHolidaysData $fetchHolidaysData, Request $request)
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

            $entityManager = $this->getDoctrine()->getManager();

            foreach ($holidayData as $singleHoliday){
                $holidayEntity = new HolidaySearch();
                $holidayEntity->setYear($singleHoliday['date']['year']);
                $holidayEntity->setCountry($formData->getCountry());

                $langEn = null;
                foreach ($singleHoliday['name'] as $name){
                    if($name['lang'] == 'en'){
                        $langEn = $name;
                        break;
                    }
                }
                $holidayEntity->setHolidayName($langEn['text']);
                $holidayEntity->setHolidayDate(new DateTime($singleHoliday['date']['day'].'-'.$singleHoliday['date']['month'].'-'.$singleHoliday['date']['year']));
                $status = $fetchHolidaysData->isWorkDay(new DateTime($singleHoliday['date']['day'].'-'.$singleHoliday['date']['month'].'-'.$singleHoliday['date']['year']), $formData->getCountry());

                if(isset($status["error"])){
                    $this->addFlash('error', $status["error"]);
                    return $this->render('main/index.html.twig', [
                        'searchForm' => $searchForm->createView(),
                    ]);
                }

                if($status["isWorkDay"]){
                    $holidayEntity->setHolidayStatus('Work Day');
                }else{
                    $holidayEntity->setHolidayStatus('Free Day');
                }

                $entityManager->persist($holidayEntity);
            }
            $entityManager->flush();

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
