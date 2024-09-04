<?php

namespace Tests\Unit\Domain;

use App\Exceptions\MissedJsonStructureException;
use App\Interfaces\VerifyService;
use App\Services\DomainService;
use App\Services\FakeVerifyService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DomainServiceTest extends TestCase
{
    private VerifyService $verifyService;

    public function setUp(): void
    {
        $this->verifyService = app()->make(FakeVerifyService::class);
        parent::setUp();
    }

    #[DataProvider('provideValidData')]
    #[Test]
    public function setValidJsonStructure($json)
    {
        $domainService = new DomainService($this->verifyService);
        $domainService->setJsonStructure($json);

        $this->assertTrue(true);
    }

    #[DataProvider('provideInvalidData')]
    #[Test]
    public function setInvalidJsonStructure($json)
    {
        $this->expectException(MissedJsonStructureException::class);
        $this->expectExceptionMessage('Invalid JSON structure.');

        $domainService = new DomainService($this->verifyService);
        $domainService->setJsonStructure($json);
    }

    public static function provideValidData()
    {
        return [['json' => '{"data":{"id":"63c79bd9303530645d1cca00","name":"Certificate of Completion","recipient":{"name":"Marty McFly","email":"marty.mcfly@gmail.com"},"issuer":{"name":"Accredify","identityProof":{"type":"DNS-DID","key":"did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller","location":"ropstore.accredify.io"}},"issued":"2022-12-23T00:00:00+08:00"},"signature":{"type":"SHA3MerkleProof","targetHash":"ad92d4a217c414d6c16ee538934b099ae7b03baa2b60914929961e1906a08767"}}']];
    }

    public static function provideInvalidData()
    {
        return [['json' => 'abc']];
    }
}
