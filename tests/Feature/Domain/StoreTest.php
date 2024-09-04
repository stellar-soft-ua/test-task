<?php

namespace Tests\Feature\Domain;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreTest extends TestCase
{
    protected $seed = true;

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        Auth::loginUsingId(1);
    }

    #[DataProvider('provideValidData')]
    #[Test]
    public function checkValidFileSubmission($dataFile): void
    {
        $response = $this->post('/api/domain/store', ['file' => $dataFile]);

        $this->assertDatabaseHas('domains', [
            'id' => 1,
            'user_id' => 1,
            'file_type' => 0,
            'verification_result' => 0,
        ]);

        $response->assertStatus(200);
    }


    #[DataProvider('provideInvalidData')]
    #[Test]
    public function checkInvalidFileSubmission($dataFile): void
    {
        $response = $this->post('/api/domain/store', ['file' => $dataFile]);

        $this->assertDatabaseHas('domains', [
            'id' => 1,
            'user_id' => 1,
            'file_type' => 0,
            'verification_result' => 3,
        ]);

        $response->assertStatus(200);
    }

    public static function provideValidData()
    {
        $file = UploadedFile::fake()->createWithContent('file.json', '{"data":{"id":"63c79bd9303530645d1cca00","name":"Certificate of Completion","recipient":{"name":"Marty McFly","email":"marty.mcfly@gmail.com"},"issuer":{"name":"Accredify","identityProof":{"type":"DNS-DID","key":"did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller","location":"ropstore.accredify.io"}},"issued":"2022-12-23T00:00:00+08:00"},"signature":{"type":"SHA3MerkleProof","targetHash":"ad92d4a217c414d6c16ee538934b099ae7b03baa2b60914929961e1906a08767"}}');

        return [['dataFile' => $file]];
    }

    public static function provideInvalidData()
    {
        $file = UploadedFile::fake()->createWithContent('file.json', '{"data":{"id":"99c79bd9303530645d1cca00","name":"Certificate of Completion","recipient":{"name":"Jhon Smith","email":"jhon.smith@gmail.com"},"issuer":{"name":"Accredify","identityProof":{"type":"DNS-DID","key":"did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller","location":"ropstore.accredify.io"}},"issued":"2022-12-23T00:00:00+08:00"},"signature":{"type":"SHA3MerkleProof","targetHash":"ad92d4a217c414d6c16ee538934b099ae7b03baa2b60914929961e1906a08766"}}');

        return [['dataFile' => $file]];
    }
}
