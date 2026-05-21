<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Publikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublikasiCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();

        // Register MySQL functions for SQLite compatibility
        if (DB::getDriverName() === 'sqlite') {
            DB::connection()->getPdo()->sqliteCreateFunction('YEAR', function ($date) {
                return $date ? date('Y', strtotime($date)) : null;
            }, 1);
            DB::connection()->getPdo()->sqliteCreateFunction('MONTH', function ($date) {
                return $date ? date('m', strtotime($date)) : null;
            }, 1);
        }
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_admin_publikasi(): void
    {
        $response = $this->get(route('admin.publikasi.index'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== INDEX ====================

    public function test_admin_can_view_publikasi_list(): void
    {
        $this->actingAs($this->admin, 'admin');
        Publikasi::factory()->count(3)->create();

        $response = $this->get(route('admin.publikasi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.publikasi.index');
        $response->assertViewHas('publikasi');
    }

    // ==================== CREATE ====================

    public function test_admin_can_view_create_publikasi_form(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.publikasi.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.publikasi.create');
    }

    public function test_admin_can_create_publikasi(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'APBDes 2024',
            'deskripsi' => 'Dokumen APBDes tahun 2024',
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'file_dokumen' => UploadedFile::fake()->create('apbdes-2024.pdf', 1000, 'application/pdf'),
            'tanggal_publikasi' => '2024-01-15',
            'status' => 'published',
        ];

        $response = $this->post(route('admin.publikasi.store'), $data);

        $response->assertRedirect(route('admin.publikasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('publikasis', [
            'judul' => 'APBDes 2024',
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'status' => 'published',
        ]);
    }

    // ==================== UPDATE ====================

    public function test_admin_can_update_publikasi(): void
    {
        $this->actingAs($this->admin, 'admin');

        $publikasi = Publikasi::factory()->create([
            'judul' => 'Original Title',
            'tahun' => 2023,
        ]);

        $response = $this->put(route('admin.publikasi.update', $publikasi), [
            'judul' => 'Updated Title',
            'deskripsi' => 'Updated description',
            'kategori' => $publikasi->kategori,
            'tahun' => 2024,
            'tanggal_publikasi' => '2024-06-01',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.publikasi.index'));

        $this->assertDatabaseHas('publikasis', [
            'id' => $publikasi->id,
            'judul' => 'Updated Title',
            'tahun' => 2024,
        ]);
    }

    // ==================== DELETE ====================

    public function test_admin_can_delete_publikasi(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $publikasi = Publikasi::factory()->create();

        $response = $this->delete(route('admin.publikasi.destroy', $publikasi));

        $response->assertRedirect(route('admin.publikasi.index'));
        $this->assertDatabaseMissing('publikasis', ['id' => $publikasi->id]);
    }

    public function test_admin_can_bulk_delete_publikasi(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $pub1 = Publikasi::factory()->create();
        $pub2 = Publikasi::factory()->create();
        $pub3 = Publikasi::factory()->create();

        // bulkDelete returns JSON, not redirect
        $response = $this->postJson(route('admin.publikasi.bulk-delete'), [
            'ids' => [$pub1->id, $pub2->id]
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('publikasis', ['id' => $pub1->id]);
        $this->assertDatabaseMissing('publikasis', ['id' => $pub2->id]);
        $this->assertDatabaseHas('publikasis', ['id' => $pub3->id]);
    }

    // ==================== VALIDATION ====================

    public function test_publikasi_validation_requires_judul(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.publikasi.store'), [
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'tanggal_publikasi' => '2024-01-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('judul');
    }

    public function test_publikasi_validation_requires_kategori(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'tahun' => 2024,
            'tanggal_publikasi' => '2024-01-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('kategori');
    }

    public function test_publikasi_validation_requires_tahun(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'kategori' => 'APBDes',
            'tanggal_publikasi' => '2024-01-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('tahun');
    }

    public function test_publikasi_file_validation_only_accepts_pdf(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'tanggal_publikasi' => '2024-01-01',
            'status' => 'draft',
            'file_dokumen' => UploadedFile::fake()->image('image.jpg'),
        ]);

        $response->assertSessionHasErrors('file_dokumen');
    }

    public function test_publikasi_kategori_enum_validation(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'kategori' => 'InvalidCategory',
            'tahun' => 2024,
            'tanggal_publikasi' => '2024-01-01',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('kategori');
    }

    // ==================== PUBLIC PAGE ====================

    public function test_only_published_publikasi_shown_on_public_page(): void
    {
        $published = Publikasi::factory()->published()->create();
        $draft = Publikasi::factory()->draft()->create();

        $response = $this->get(route('publikasi.index'));

        $response->assertSee($published->judul);
        $response->assertDontSee($draft->judul);
    }

    public function test_publikasi_can_be_filtered_by_kategori(): void
    {
        $apbdes = Publikasi::factory()->published()->create(['kategori' => 'APBDes']);
        $rpjmdes = Publikasi::factory()->published()->create(['kategori' => 'RPJMDes']);

        $response = $this->get(route('publikasi.index', ['kategori' => 'APBDes']));

        $response->assertStatus(200);
        $response->assertSee($apbdes->judul);
    }

    public function test_admin_can_view_publikasi_detail(): void
    {
        $this->actingAs($this->admin, 'admin');
        $publikasi = Publikasi::factory()->create();

        $response = $this->get(route('admin.publikasi.show', $publikasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.publikasi.show');
        $response->assertViewHas('publikasi');
        $response->assertSee($publikasi->judul);
    }

    public function test_admin_can_view_edit_publikasi_form(): void
    {
        $this->actingAs($this->admin, 'admin');
        $publikasi = Publikasi::factory()->create();

        $response = $this->get(route('admin.publikasi.edit', $publikasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.publikasi.edit');
        $response->assertViewHas('publikasi');
    }

    public function test_admin_can_create_publikasi_with_thumbnail(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $data = [
            'judul' => 'APBDes 2025 dengan Thumbnail',
            'deskripsi' => 'Dokumen APBDes tahun 2025',
            'kategori' => 'APBDes',
            'tahun' => 2025,
            'file_dokumen' => UploadedFile::fake()->create('apbdes-2025.pdf', 1000, 'application/pdf'),
            'thumbnail' => UploadedFile::fake()->image('thumbnail-apbdes.jpg'),
            'tanggal_publikasi' => '2025-01-15',
            'status' => 'published',
        ];

        $response = $this->post(route('admin.publikasi.store'), $data);

        $response->assertRedirect(route('admin.publikasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('publikasis', [
            'judul' => 'APBDes 2025 dengan Thumbnail',
            'kategori' => 'APBDes',
            'tahun' => 2025,
            'status' => 'published',
        ]);

        $publikasi = Publikasi::where('judul', 'APBDes 2025 dengan Thumbnail')->first();
        $this->assertNotNull($publikasi->thumbnail);
        Storage::disk('public')->assertExists($publikasi->file_dokumen);
        Storage::disk('public')->assertExists($publikasi->thumbnail);
    }

    public function test_admin_can_update_publikasi_with_new_files(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin, 'admin');

        $oldFile = UploadedFile::fake()->create('old.pdf', 500, 'application/pdf');
        $oldThumbnail = UploadedFile::fake()->image('old-thumb.jpg');

        $oldFilePath = $oldFile->store('publikasi', 'public');
        $oldThumbPath = $oldThumbnail->store('publikasi/thumbnails', 'public');

        $publikasi = Publikasi::factory()->create([
            'file_dokumen' => $oldFilePath,
            'thumbnail' => $oldThumbPath,
        ]);

        Storage::disk('public')->assertExists($oldFilePath);
        Storage::disk('public')->assertExists($oldThumbPath);

        $newFile = UploadedFile::fake()->create('new.pdf', 800, 'application/pdf');
        $newThumbnail = UploadedFile::fake()->image('new-thumb.jpg');

        $response = $this->put(route('admin.publikasi.update', $publikasi->id), [
            'judul' => 'Updated Title',
            'kategori' => $publikasi->kategori,
            'tahun' => 2026,
            'tanggal_publikasi' => '2026-01-01',
            'status' => 'published',
            'file_dokumen' => $newFile,
            'thumbnail' => $newThumbnail,
        ]);

        $response->assertRedirect(route('admin.publikasi.index'));

        $publikasi->refresh();
        $this->assertDatabaseHas('publikasis', [
            'id' => $publikasi->id,
            'judul' => 'Updated Title',
            'tahun' => 2026,
        ]);

        // Old files should be deleted
        Storage::disk('public')->assertMissing($oldFilePath);
        Storage::disk('public')->assertMissing($oldThumbPath);

        // New files should exist
        Storage::disk('public')->assertExists($publikasi->file_dokumen);
        Storage::disk('public')->assertExists($publikasi->thumbnail);
    }

    public function test_admin_cannot_bulk_delete_without_ids(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->postJson(route('admin.publikasi.bulk-delete'), [
            'ids' => []
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Tidak ada publikasi yang dipilih'
        ]);
    }

    public function test_public_can_download_publikasi(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');
        $filePath = $file->store('publikasi', 'public');

        $publikasi = Publikasi::factory()->published()->create([
            'file_dokumen' => $filePath,
            'jumlah_download' => 10,
        ]);

        $response = $this->get(route('publikasi.download', $publikasi->id));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));

        $publikasi->refresh();
        // Downloads counter should increment
        $this->assertEquals(11, $publikasi->jumlah_download);
    }
}

