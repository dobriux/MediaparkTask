<?php

namespace App\Entity;

use App\Repository\HolidaySearchRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HolidaySearchRepository::class)
 */
class HolidaySearch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 2011,
     *      max = 32767,
     *      notInRangeMessage = "The year must be between {{ min }} and {{ max }}",
     * )
     */
    private $year;

    /**
     * @ORM\Column(type="string")
     */
    private $holidayName;

    /**
     * @ORM\Column(type="string")
     */
    private $holidayStatus;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $holidayDate;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear($year): void
    {
        $this->year = $year;
    }

    public function getHolidayName(): string
    {
        return $this->holidayName;
    }

    public function setHolidayName($holidayName): void
    {
        $this->holidayName = $holidayName;
    }

    public function getHolidayStatus(): string
    {
        return $this->holidayStatus;
    }

    public function setHolidayStatus($holidayStatus): void
    {
        $this->holidayStatus = $holidayStatus;
    }

    public function getHolidayDate(): DateTime
    {
        return $this->holidayDate;
    }

    public function setHolidayDate($holidayDate): void
    {
        $this->holidayDate = $holidayDate;
    }


}
