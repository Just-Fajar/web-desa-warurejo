<?php

namespace Tests\Unit\Services;

use App\Models\Galeri;
use App\Repositories\GaleriRepository;
use App\Services\GaleriService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class GaleriServiceTest extends TestCase
{
    private $repository;

    private GaleriService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(GaleriRepository::class);
        $this->service = new GaleriService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ==================== READ METHODS ====================

    public function test_get_all_galeri()
    {
        $this->repository->shouldReceive('all')->once()->andReturn(collect());
        $result = $this->service->getAllGaleri();
        $this->assertCount(0, $result);
    }

    public function test_get_paginated_galeri_default()
    {
        $expected = collect([new Galeri(['judul' => 'Test'])]);
        $this->repository->shouldReceive('paginate')->once()->with(15)->andReturn($expected);
        $result = $this->service->getPaginatedGaleri();
        $this->assertEquals($expected, $result);
    }

    public function test_get_active_galeri_default()
    {
        $expected = collect([new Galeri(['judul' => 'Test'])]);
        $this->repository->shouldReceive('getActive')->once()->with(24)->andReturn($expected);
        $result = $this->service->getActiveGaleri();
        $this->assertEquals($expected, $result);
    }

    public function test_get_latest_galeri_default()
    {
        $expected = collect([new Galeri(['judul' => 'Test'])]);
        $this->repository->shouldReceive('getLatest')->once()->with(6)->andReturn($expected);
        $result = $this->service->getLatestGaleri();
        $this->assertEquals($expected, $result);
    }

    public function test_get_galeri_by_kategori()
    {
        $expected = collect([new Galeri(['judul' => 'Test'])]);
        $this->repository->shouldReceive('getByKategori')
            ->once()->with('kegiatan', 24)->andReturn($expected);
        $result = $this->service->getGaleriByKategori('kegiatan');
        $this->assertEquals($expected, $result);
    }

    public function test_get_galeri_by_id()
    {
        $galeri = new Galeri(['judul' => 'Test']);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($galeri);
        $result = $this->service->getGaleriById(1);
        $this->assertEquals('Test', $result->judul);
    }

    // ==================== CREATE ====================

    public function test_create_galeri_sets_tanggal_if_empty()
    {
        $this->repository->shouldReceive('create')
            ->once()
            ->andReturnUsing(function ($data) {
                $this->assertArrayHasKey('tanggal', $data);

                return new Galeri($data);
            });

        Cache::shouldReceive('forget')->once()->with('home.galeri');

        $result = $this->service->createGaleri(['judul' => 'Test']);
        $this->assertInstanceOf(Galeri::class, $result);
    }

    // ==================== DELETE ====================

    public function test_delete_galeri_removes_image()
    {
        Storage::fake('public');
        Storage::disk('public')->put('galeri/test.jpg', 'dummy');

        $galeri = new Galeri(['gambar' => 'galeri/test.jpg']);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($galeri);
        $this->repository->shouldReceive('delete')->once()->with(1)->andReturn(true);
        Cache::shouldReceive('forget')->once()->with('home.galeri');

        $result = $this->service->deleteGaleri(1);
        $this->assertTrue($result);

        Storage::disk('public')->assertMissing('galeri/test.jpg');
    }

    public function test_delete_galeri_without_image()
    {
        $galeri = new Galeri(['gambar' => null]);
        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($galeri);
        $this->repository->shouldReceive('delete')->once()->with(1)->andReturn(true);
        Cache::shouldReceive('forget')->once()->with('home.galeri');

        $result = $this->service->deleteGaleri(1);
        $this->assertTrue($result);
    }

    // ==================== UPDATE ====================

    public function test_update_galeri_without_new_image()
    {
        $galeri = new Galeri(['gambar' => 'galeri/old.jpg', 'judul' => 'Old Title']);

        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($galeri);
        $this->repository->shouldReceive('update')->once()->with(1, Mockery::on(function ($data) {
            $this->assertArrayNotHasKey('gambar', $data);
            $this->assertEquals('New Title', $data['judul']);

            return true;
        }))->andReturn(true);

        Cache::shouldReceive('forget')->once()->with('home.galeri');

        $result = $this->service->updateGaleri(1, ['judul' => 'New Title', 'gambar' => 'galeri/old.jpg']);
        $this->assertTrue($result);
    }

    public function test_update_galeri_with_new_image()
    {
        Storage::fake('public');
        Storage::disk('public')->put('galeri/old.jpg', 'dummy');

        $galeri = new Galeri(['gambar' => 'galeri/old.jpg', 'judul' => 'Old Title']);

        $this->repository->shouldReceive('find')->once()->with(1)->andReturn($galeri);
        $this->repository->shouldReceive('update')->once()->with(1, Mockery::on(function ($data) {
            $this->assertArrayHasKey('gambar', $data);
            $this->assertStringContainsString('galeri/', $data['gambar']);
            $this->assertEquals('New Title', $data['judul']);

            return true;
        }))->andReturn(true);

        Cache::shouldReceive('forget')->once()->with('home.galeri');

        $newImage = \Illuminate\Http\UploadedFile::fake()->image('new.jpg', 100, 100);

        $result = $this->service->updateGaleri(1, ['judul' => 'New Title', 'gambar' => $newImage]);
        $this->assertTrue($result);

        Storage::disk('public')->assertMissing('galeri/old.jpg');
    }

    // ==================== OTHER METHODS ====================

    public function test_get_categories_with_count()
    {
        $expected = collect([['kategori' => 'Kegiatan', 'count' => 5]]);
        $this->repository->shouldReceive('getCategoriesWithCount')->once()->andReturn($expected);

        $result = $this->service->getCategoriesWithCount();
        $this->assertEquals($expected, $result);
    }

    public function test_get_by_date_range()
    {
        $expected = collect([new Galeri(['judul' => 'Test'])]);
        $this->repository->shouldReceive('getByDateRange')->once()->with('2026-01-01', '2026-01-31', 24)->andReturn($expected);

        $result = $this->service->getByDateRange('2026-01-01', '2026-01-31');
        $this->assertEquals($expected, $result);
    }

    public function test_get_recent_galeri()
    {
        $expected = collect([new Galeri(['judul' => 'Test'])]);
        $this->repository->shouldReceive('getRecent')->once()->with(12)->andReturn($expected);

        $result = $this->service->getRecentGaleri();
        $this->assertEquals($expected, $result);
    }
}
