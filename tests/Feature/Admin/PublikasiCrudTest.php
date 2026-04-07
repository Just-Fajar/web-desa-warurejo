<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Publikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PublikasiCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    /**
     * Test guest cannot access admin publikasi
     */
    public function test_guest_cannot_access_admin_publikasi(): void
    {
        $response = $this->get(route('admin.publikasi.index'));
        
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test admin can view publikasi list
     */
    public function test_admin_can_view_publikasi_list(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        Publikasi::factory()->count(3)->create();
        
        $response = $this->get(route('admin.publikasi.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.publikasi.index');
        $response->assertViewHas('publikasi');
    }

    /**
     * Test admin can view create publikasi form
     */
    public function test_admin_can_view_create_publikasi_form(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->get(route('admin.publikasi.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.publikasi.create');
    }

    /**
     * Test admin can create publikasi
     */
    public function test_admin_can_create_publikasi(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $data = [
            'judul' => 'APBDes 2024',
            'deskripsi' => 'Dokumen APBDes tahun 2024',
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'file' => UploadedFile::fake()->create('apbdes-2024.pdf', 1000, 'application/pdf'),
            'status' => 'published',
        ];
        
        $response = $this->post(route('admin.publikasi.store'), $data);
        
        $response->assertRedirect(route('admin.publikasi.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('publikasi', [
            'judul' => 'APBDes 2024',
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'status' => 'published',
        ]);
    }

    /**
     * Test admin can update publikasi
     */
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
            'status' => 'published',
        ]);
        
        $response->assertRedirect(route('admin.publikasi.index'));
        
        $this->assertDatabaseHas('publikasi', [
            'id' => $publikasi->id,
            'judul' => 'Updated Title',
            'tahun' => 2024,
        ]);
    }

    /**
     * Test admin can delete publikasi
     */
    public function test_admin_can_delete_publikasi(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $publikasi = Publikasi::factory()->create();
        
        $response = $this->delete(route('admin.publikasi.destroy', $publikasi));
        
        $response->assertRedirect(route('admin.publikasi.index'));
        
        $this->assertDatabaseMissing('publikasi', ['id' => $publikasi->id]);
    }

    /**
     * Test admin can bulk delete publikasi
     */
    public function test_admin_can_bulk_delete_publikasi(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $pub1 = Publikasi::factory()->create();
        $pub2 = Publikasi::factory()->create();
        $pub3 = Publikasi::factory()->create();
        
        $response = $this->post(route('admin.publikasi.bulk-delete'), [
            'ids' => [$pub1->id, $pub2->id]
        ]);
        
        $response->assertRedirect(route('admin.publikasi.index'));
        
        $this->assertDatabaseMissing('publikasi', ['id' => $pub1->id]);
        $this->assertDatabaseMissing('publikasi', ['id' => $pub2->id]);
        $this->assertDatabaseHas('publikasi', ['id' => $pub3->id]);
    }

    /**
     * Test publikasi validation requires judul
     */
    public function test_publikasi_validation_requires_judul(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.publikasi.store'), [
            'kategori' => 'APBDes',
            'tahun' => 2024,
        ]);
        
        $response->assertSessionHasErrors('judul');
    }

    /**
     * Test publikasi validation requires kategori
     */
    public function test_publikasi_validation_requires_kategori(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'tahun' => 2024,
        ]);
        
        $response->assertSessionHasErrors('kategori');
    }

    /**
     * Test publikasi validation requires tahun
     */
    public function test_publikasi_validation_requires_tahun(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'kategori' => 'APBDes',
        ]);
        
        $response->assertSessionHasErrors('tahun');
    }

    /**
     * Test publikasi file validation only accepts PDF
     */
    public function test_publikasi_file_validation_only_accepts_pdf(): void
    {
        Storage::fake('public');
        
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'kategori' => 'APBDes',
            'tahun' => 2024,
            'file' => UploadedFile::fake()->image('image.jpg'),
        ]);
        
        $response->assertSessionHasErrors('file');
    }

    /**
     * Test publikasi kategori enum validation
     */
    public function test_publikasi_kategori_enum_validation(): void
    {
        $this->actingAs($this->admin, 'admin');
        
        $response = $this->post(route('admin.publikasi.store'), [
            'judul' => 'Test',
            'kategori' => 'InvalidCategory',
            'tahun' => 2024,
        ]);
        
        $response->assertSessionHasErrors('kategori');
    }

    /**
     * Test only published publikasi shown on public page
     */
    public function test_only_published_publikasi_shown_on_public_page(): void
    {
        $published = Publikasi::factory()->published()->create();
        $draft = Publikasi::factory()->draft()->create();
        
        $response = $this->get(route('publikasi.index'));
        
        $response->assertSee($published->judul);
        $response->assertDontSee($draft->judul);
    }

    /**
     * Test publikasi can be filtered by kategori
     */
    public function test_publikasi_can_be_filtered_by_kategori(): void
    {
        $apbdes = Publikasi::factory()->published()->create(['kategori' => 'APBDes']);
        $rpjmdes = Publikasi::factory()->published()->create(['kategori' => 'RPJMDes']);
        
        $response = $this->get(route('publikasi.index', ['kategori' => 'APBDes']));
        
        $response->assertSee($apbdes->judul);
        $response->assertDontSee($rpjmdes->judul);
    }

    /**
     * Test publikasi can be downloaded
     */
    public function test_publikasi_can_be_downloaded(): void
    {
        Storage::fake('public');
        
        $publikasi = Publikasi::factory()->create([
            'file_path' => 'publikasi/test.pdf'
        ]);
        
        // Create fake file
        Storage::disk('public')->put('publikasi/test.pdf', 'fake pdf content');
        
        $response = $this->get(route('publikasi.download', $publikasi));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
