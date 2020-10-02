<?php


namespace App\Tests\Repository;


use App\Entity\HolidaySearch;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HolidaySearchRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchByYearAndCountry()
    {
        $product = $this->entityManager
            ->getRepository(HolidaySearch::class)
            ->findByYearAndCountry('ltu', 2020);
        ;

        $this->assertSame(2020, $product[1]->getYear());
        $this->assertNotSame(2021, $product[1]->getYear());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}