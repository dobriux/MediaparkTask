<?php


namespace App\Service;


use DateTime;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchHolidaysData
{
    private $client;
    private $dataUrl;
    private $country;
    private $region;
    private $holidayType;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->dataUrl = "https://kayaposoft.com/enrico/json/v2.0";
        $this->region = "bw";
        $this->country = "lt";
        $this->holidayType = "all";
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function setRegion($region): void
    {
        $this->region = $region;
    }

    public function getHolidayType()
    {
        return $this->holidayType;
    }

    public function setHolidayType($holidayType): void
    {
        $this->holidayType = $holidayType;
    }

    public function getHolidaysForMonth(int $month, int $year) : array
    {
        return $this->fetch("getHolidaysForMonth",[
            'month' => $month,
            'year' => $year,
            'region' => $this->getRegion(),
            'country' => $this->getCountry(),
            'holidayType' => $this->getHolidayType()
        ]);
    }

    public function getHolidaysForYear(int $year) : array
    {
        return $this->fetch("getHolidaysForYear",[
            'year' => $year,
            'country' => $this->getCountry(),
            'holidayType' => $this->getHolidayType()
        ]);
    }

    public function getHolidaysForDateRange(DateTime $fromDate, DateTime $toDate) : array
    {
        return $this->fetch("getHolidaysForDateRange",[
            'fromDate' => $fromDate->format('d-m-Y'),
            'toDate' => $toDate->format('d-m-Y'),
            'region' => $this->getRegion(),
            'country' => $this->getCountry(),
            'holidayType' => $this->getHolidayType()
        ]);
    }

    public function isPublicHoliday(DateTime $date) : array
    {
        return $this->fetch("isPublicHoliday",[
            'date' => $date->format('d-m-Y'),
            'country' => $this->getCountry(),
        ]);
    }

    public function isWorkDay(DateTime $date) : array
    {
        return $this->fetch("isWorkDay",[
            'date' => $date->format('d-m-Y'),
            'country' => $this->getCountry(),
        ]);
    }

    public function getSupportedCountries() : array
    {
        return $this->fetch("getSupportedCountries");
    }

    public function whereIsPublicHoliday(DateTime $date) : array
    {
        return $this->fetch("whereIsPublicHoliday",[
            'date' => $date->format('d-m-Y'),
        ]);
    }

    private function fetch(string $action ,array $parameters = NULL): array
    {
        $parameters['action'] = $action;

        try{

            $response = $this->client->request(
                'GET',
                $this->dataUrl,[
                    'query' => $parameters,
                ]
            );
                return $response->toArray();

        }catch (TransportExceptionInterface $e){
            throw new Exception($e->getMessage());
        } catch (ClientExceptionInterface $e) {
            throw new Exception($e->getMessage());
        } catch (DecodingExceptionInterface $e) {
            throw new Exception($e->getMessage());
        } catch (RedirectionExceptionInterface $e) {
            throw new Exception($e->getMessage());
        } catch (ServerExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }
}