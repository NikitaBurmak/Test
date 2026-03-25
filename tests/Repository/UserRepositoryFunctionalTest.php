<?php

namespace App\Tests\Repository;

use App\Document\User;
use App\Repository\UserRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group functional
 */
class UserRepositoryFunctionalTest extends KernelTestCase
{
    private DocumentManager $dm;
    private UserRepository $repository;

    /**
     * @throws MongoDBException
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $this->dm = static::getContainer()->get(DocumentManager::class);
        $this->repository = new UserRepository($this->dm);

        echo "Using MongoDB: " . $this->dm->getDocumentDatabase(User::class)->getDatabaseName() . PHP_EOL;

        $this->dm->getDocumentCollection(User::class)->deleteMany([]);
    }

    /**
     * @throws MongoDBException
     */
    protected function tearDown(): void
    {
        if (isset($this->dm)) {
            $this->dm->getDocumentCollection(User::class)->deleteMany([]);
        }
        parent::tearDown();
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function testFindUsersWithAggregationReturnsArray(): void
    {
        $this->createTestUsers(5);

        $result = $this->repository->findUsersWithAggregation();

        $this->assertIsArray($result);
        $this->assertCount(5, $result);
    }

    public function testFindUsersWithAggregationWithLimit(): void
    {
        $this->createTestUsers(10);

        $result = $this->repository->findUsersWithAggregation('asc', 3, 0);

        $this->assertCount(3, $result);
    }

    public function testFindUsersWithAggregationWithCursor(): void
    {
        $this->createTestUsers(10);

        $result1 = $this->repository->findUsersWithAggregation('asc', 5, 0);
        $this->assertCount(5, $result1);

        $lastUser = end($result1);
        $cursor = is_array($lastUser) ? ($lastUser['id'] ?? 0) : ($lastUser->getId() ?? 0);

        $result2 = $this->repository->findUsersWithAggregation('asc', 5, $cursor);
        $this->assertCount(5, $result2);
    }

    public function testFindUsersWithAggregationWithDescSort(): void
    {
        $this->createTestUsers(5);

        $result = $this->repository->findUsersWithAggregation('desc', 10, 0);

        $this->assertCount(5, $result);
    }

    public function testCountUsersReturnsCorrectCount(): void
    {
        $this->createTestUsers(7);

        $count = $this->repository->countUsers();

        $this->assertEquals(7, $count);
    }

    public function testCountUsersReturnsZeroWhenEmpty(): void
    {
        $count = $this->repository->countUsers();

        $this->assertEquals(0, $count);
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    private function createTestUsers(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $user = new User();
            $user->setFirstName("User$i");
            $user->setLastName("Test");
            $user->setPhoneNumbers(["+123456789$i"]);
            $user->setIp("192.168.1.$i");
            $user->setCountry("US");

            $this->dm->persist($user);
        }
        $this->dm->flush();
    }
}
