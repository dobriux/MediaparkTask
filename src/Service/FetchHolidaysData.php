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
    private $holidayType;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->dataUrl = "https://kayaposoft.com/enrico/json/v2.0";
        $this->holidayType = "all";
    }

    public function getHolidayType()
    {
        return $this->holidayType;
    }

    public function setHolidayType($holidayType): void
    {
        $this->holidayType = $holidayType;
    }

    public function getHolidaysForMonth(int $month, int $year, string $country = 'lt', string $region = 'bw') : array
    {
        return $this->fetch("getHolidaysForMonth",[
            'month' => $month,
            'year' => $year,
            'region' => $region,
            'country' => $country,
            'holidayType' => $this->getHolidayType()
        ]);
    }

    public function getHolidaysForYear(int $year, string $country = 'lt') : array
    {
        return $this->fetch("getHolidaysForYear",[
            'year' => $year,
            'country' => $country,
            'holidayType' => $this->getHolidayType()
        ]);
    }

    public function getHolidaysForDateRange(DateTime $fromDate, DateTime $toDate, string $country = 'lt', string $region = 'bw') : array
    {
        return $this->fetch("getHolidaysForDateRange",[
            'fromDate' => $fromDate->format('d-m-Y'),
            'toDate' => $toDate->format('d-m-Y'),
            'region' => $region,
            'country' => $country,
            'holidayType' => $this->getHolidayType()
        ]);
    }

    public function isPublicHoliday(DateTime $date, string $country = 'lt') : array
    {
        return $this->fetch("isPublicHoliday",[
            'date' => $date->format('d-m-Y'),
            'country' => $country,
        ]);
    }

    public function isWorkDay(DateTime $date, string $country = 'lt') : array
    {
        return $this->fetch("isWorkDay",[
            'date' => $date->format('d-m-Y'),
            'country' => $country,
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

    public function getSupportedCountriesArray() : array
    {
        $countries = null;
        foreach ($this->getSupportedCountries() as $supportedCountries){
            $countries[$supportedCountries['fullName']] = $supportedCountries['countryCode'];
        }
        return $countries;
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