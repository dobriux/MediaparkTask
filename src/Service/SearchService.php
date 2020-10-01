<?php


namespace App\Service;


use App\Entity\HolidaySearch;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class SearchService
{
    private $entityManager;
    private $fetchHolidaysData;

    public function __construct(EntityManagerInterface $entityManager, FetchHolidaysData $fetchHolidaysData)
    {
        $this->entityManager = $entityManager;
        $this->fetchHolidaysData = $fetchHolidaysData;
    }

    public function searchResultsToDatabase(array $array, string $country){

        foreach ($array as $singleHoliday){
            $holidayEntity = new HolidaySearch();
            $holidayEntity->setYear($singleHoliday['date']['year']);
            $holidayEntity->setCountry($country);

            $langEn = null;
            foreach ($singleHoliday['name'] as $name){
                if($name['lang'] == 'en'){
                    $langEn = $name;
                    break;
                }
            }
            $holidayEntity->setHolidayName($langEn['text']);
            $holidayEntity->setHolidayDate(new DateTime($singleHoliday['date']['day'].'-'.$singleHoliday['date']['month'].'-'.$singleHoliday['date']['year']));
            $statusWorkday = $this->fetchHolidaysData->isWorkDay(new DateTime($singleHoliday['date']['day'].'-'.$singleHoliday['date']['month'].'-'.$singleHoliday['date']['year']), $country);

            if($singleHoliday['holidayType'] === "public_holiday"){
                $holidayEntity->setHolidayStatus('Holiday');
            }elseif($statusWorkday["isWorkDay"]){
                $holidayEntity->setHolidayStatus('Work Day');
            }else{
                $holidayEntity->setHolidayStatus('Free Day');
            }

            $this->entityManager->persist($holidayEntity);
        }
        $this->entityManager->flush();
    }
}