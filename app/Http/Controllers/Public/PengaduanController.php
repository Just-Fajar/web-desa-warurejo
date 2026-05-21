<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SEOHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengaduanRequest;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /**
     * Tampilkan daftar pengaduan publik
     * Route: GET /pengaduan
     */
    public function index(Request $request)
    {
        $query = Pengaduan::withCount('balasan');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%")
                    ->orWhere('lokasi_kejadian', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Date From
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filter Date To
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter sort
        if ($request->filled('sort')) {
            if ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $pengaduan = $query->paginate(10)->appends($request->query());

        // Stats
        $totalPengaduan = Pengaduan::count();
        $totalMenunggu = Pengaduan::where('status', 'Menunggu')->count();
        $totalDiproses = Pengaduan::where('status', 'Diproses')->count();
        $totalSelesai = Pengaduan::where('status', 'Selesai')->count();

        $seoData = SEOHelper::generateMetaTags([
            'title' => 'Forum Pengaduan - Desa Warurejo',
            'description' => 'Forum pengaduan publik Desa Warurejo. Sampaikan aspirasi dan keluhan Anda secara transparan.',
            'keywords' => 'pengaduan, forum, desa warurejo, aspirasi, transparansi',
            'type' => 'website',
        ]);

        return view('public.pengaduan.index', compact(
            'pengaduan',
            'totalPengaduan',
            'totalMenunggu',
            'totalDiproses',
            'totalSelesai',
            'seoData'
        ));
    }

    /**
     * Tampilkan detail pengaduan publik
     * Route: GET /pengaduan/{id}
     */
    public function show($id)
    {
        $pengaduan = Pengaduan::with(['balasan' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($id);

        $seoData = SEOHelper::generateMetaTags([
            'title' => $pengaduan->judul.' - Forum Pengaduan Desa Warurejo',
            'description' => \Illuminate\Support\Str::limit(strip_tags($pengaduan->isi), 160),
            'keywords' => 'pengaduan, desa warurejo, '.$pengaduan->judul,
            'type' => 'article',
        ]);

        return view('public.pengaduan.show', compact('pengaduan', 'seoData'));
    }

    /**
     * Simpan pengaduan baru dari publik
     * Route: POST /pengaduan
     */
    public function store(StorePengaduanRequest $request)
    {
        $data = $request->validated();

        // Upload lampiran jika ada
        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pengaduan', 'public');
        }

        $pengaduan = Pengaduan::create($data);

        return redirect()->route('pengaduan.show', $pengaduan->id)
            ->with('success', 'Pengaduan berhasil dikirim. Admin akan segera merespons.');
    }
}
