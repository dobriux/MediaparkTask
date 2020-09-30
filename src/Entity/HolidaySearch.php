<?php

namespace App\Entity;

use App\Repository\HolidaySearchRepository;
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
     *      notInRangeMessage = "You must be between {{ min }}cm and {{ max }}cm tall to enter",
     * )
     */
    private $year;

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


}
