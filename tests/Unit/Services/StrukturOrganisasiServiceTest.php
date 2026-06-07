<?php

namespace Tests\Unit\Services;

use App\Models\StrukturOrganisasi;
use App\Repositories\StrukturOrganisasiRepository;
use App\Services\ImageUploadService;
use App\Services\StrukturOrganisasiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class StrukturOrganisasiServiceTest extends TestCase
{
    private $repository;

    private $imageUploadService;

    private StrukturOrganisasiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(StrukturOrganisasiRepository::class);
        $this->imageUploadService = Mockery::mock(ImageUploadService::class);
        $this->service = new StrukturOrganisasiService($this->repository, $this->imageUploadService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ==================== READ ====================

    public function test_get_paginated()
    {
        $expected = collect([new StrukturOrganisasi(['nama' => 'Test'])]);
        $this->repository->shouldReceive('getPaginated')->once()->with([], 15)->andReturn($expected);
        $result = $this->service->getPaginatedStrukturOrganisasi();
        $this->assertEquals($expected, $result);
    }

    public function test_get_active()
    {
        $expected = collect([new StrukturOrganisasi(['nama' => 'Test'])]);
        $this->repository->shouldReceive('getActive')->once()->andReturn($expected);
        $result = $this->service->getActiveStrukturOrganisasi();
        $this->assertEquals($expected, $result);
    }

    public function test_get_structured_data()
    {
        \Illuminate\Support\Facades\Cache::flush();
        $expected = collect([new StrukturOrganisasi(['nama' => 'Test'])]);
        $this->repository->shouldReceive('getStructuredData')->once()->andReturn($expected);
        
        // Call first time (cache miss)
        $result = $this->service->getStructuredData();
        $this->assertEquals($expected, $result);

        // Call second time (cache hit)
        $result2 = $this->service->getStructuredData();
        $this->assertEquals($expected, $result2);
    }

    public function test_get_by_id()
    {
        $item = new StrukturOrganisasi(['nama' => 'Test']);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($item);
        $result = $this->service->getStrukturOrganisasiById(1);
        $this->assertEquals('Test', $result->nama);
    }

    // ==================== CREATE ====================

    public function test_create_with_foto()
    {
        $mockFile = Mockery::mock(\Illuminate\Http\UploadedFile::class);
        $this->imageUploadService->shouldReceive('upload')
            ->once()->with($mockFile, 'struktur-organisasi', 800, 800)
            ->andReturn('struktur-organisasi/foto.jpg');

        $this->repository->shouldReceive('create')->once()->andReturnUsing(function ($data) {
            $this->assertEquals('struktur-organisasi/foto.jpg', $data['foto']);

            return new StrukturOrganisasi($data);
        });

        $result = $this->service->createStrukturOrganisasi([
            'nama' => 'Test',
            'foto' => $mockFile,
        ]);
        $this->assertInstanceOf(StrukturOrganisasi::class, $result);
    }

    public function test_create_without_foto()
    {
        $this->repository->shouldReceive('create')->once()
            ->andReturn(new StrukturOrganisasi(['nama' => 'Test']));

        $result = $this->service->createStrukturOrganisasi(['nama' => 'Test']);
        $this->assertInstanceOf(StrukturOrganisasi::class, $result);
    }

    public function test_create_logs_and_rethrows_exception()
    {
        $this->repository->shouldReceive('create')
            ->once()->andThrow(new \Exception('DB Error'));

        Log::shouldReceive('error')->once();

        $this->expectException(\Exception::class);
        $this->service->createStrukturOrganisasi(['nama' => 'Test']);
    }

    // ==================== UPDATE ====================

    public function test_update_without_foto_keeps_existing()
    {
        $item = new StrukturOrganisasi(['nama' => 'Old', 'foto' => 'old.jpg']);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($item);
        $this->repository->shouldReceive('update')->once()->with(1, Mockery::on(function ($data) {
            return ! isset($data['foto']);
        }))->andReturn(true);

        $result = $this->service->updateStrukturOrganisasi(1, ['nama' => 'New']);
        $this->assertTrue($result);
    }

    public function test_update_with_foto_deletes_old_and_uploads_new()
    {
        Storage::fake('public');
        Storage::disk('public')->put('old.jpg', 'dummy');

        $item = new StrukturOrganisasi(['nama' => 'Old', 'foto' => 'old.jpg']);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($item);

        $mockFile = Mockery::mock(\Illuminate\Http\UploadedFile::class);
        $this->imageUploadService->shouldReceive('upload')
            ->once()->with($mockFile, 'struktur-organisasi', 800, 800)
            ->andReturn('struktur-organisasi/new.jpg');

        $this->repository->shouldReceive('update')->once()->with(1, Mockery::on(function ($data) {
            return $data['foto'] === 'struktur-organisasi/new.jpg';
        }))->andReturn(true);

        $result = $this->service->updateStrukturOrganisasi(1, ['nama' => 'New', 'foto' => $mockFile]);
        $this->assertTrue($result);
        Storage::disk('public')->assertMissing('old.jpg');
    }

    public function test_update_logs_and_rethrows_exception()
    {
        $this->repository->shouldReceive('find')->once()->with(1)->andThrow(new \Exception('DB Error'));
        Log::shouldReceive('error')->once();

        $this->expectException(\Exception::class);
        $this->service->updateStrukturOrganisasi(1, ['nama' => 'New']);
    }

    // ==================== DELETE ====================

    public function test_delete_removes_foto_from_storage()
    {
        Storage::fake('public');
        Storage::disk('public')->put('struktur-organisasi/foto.jpg', 'dummy');

        $item = new StrukturOrganisasi(['foto' => 'struktur-organisasi/foto.jpg']);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($item);
        $this->repository->shouldReceive('delete')->once()->with(1)->andReturn(true);

        $result = $this->service->deleteStrukturOrganisasi(1);
        $this->assertTrue($result);
        Storage::disk('public')->assertMissing('struktur-organisasi/foto.jpg');
    }

    public function test_delete_logs_and_rethrows_exception()
    {
        $this->repository->shouldReceive('find')->once()->with(1)->andThrow(new \Exception('DB Error'));
        Log::shouldReceive('error')->once();

        $this->expectException(\Exception::class);
        $this->service->deleteStrukturOrganisasi(1);
    }

    // ==================== BULK DELETE ====================

    public function test_bulk_delete_calls_delete_for_each_id()
    {
        Storage::fake('public');
        $item1 = new StrukturOrganisasi(['foto' => null]);
        $item2 = new StrukturOrganisasi(['foto' => null]);

        $this->repository->shouldReceive('find')->with(1)->andReturn($item1);
        $this->repository->shouldReceive('find')->with(2)->andReturn($item2);
        $this->repository->shouldReceive('delete')->with(1)->andReturn(true);
        $this->repository->shouldReceive('delete')->with(2)->andReturn(true);

        $result = $this->service->bulkDelete([1, 2]);
        $this->assertTrue($result);
    }

    public function test_bulk_delete_logs_and_rethrows_exception()
    {
        $this->repository->shouldReceive('find')->once()->with(1)->andThrow(new \Exception('DB Error'));
        Log::shouldReceive('error')->twice();

        $this->expectException(\Exception::class);
        $this->service->bulkDelete([1, 2]);
    }

    // ==================== UPDATE URUTAN ====================

    public function test_update_urutan()
    {
        $this->repository->shouldReceive('updateUrutan')
            ->once()->with(1, 5)->andReturn(true);

        $result = $this->service->updateUrutan(1, 5);
        $this->assertTrue($result);
    }
}
