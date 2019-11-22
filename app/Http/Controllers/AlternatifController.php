<?php

namespace App\Http\Controllers;

use App\Alternatif;
use App\Bobot;
use App\Kriteria;
use App\ReferensiKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlternatifController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil daftar kriteria
        $l_kriteria = Kriteria::all();
        $data['l_kriteria'] = [];

        foreach ($l_kriteria as $kriteria) {
            $des_kriteria = [
                'id' => $kriteria->id_kriteria,
                'nama' => $kriteria->nama,
            ];

            array_push($data['l_kriteria'], $des_kriteria);
        }

        // Ambil daftar alternatif
        $l_alternatif = Alternatif::all();
        $data['l_alternatif'] = [];

        foreach ($l_alternatif as $alternatif) {
            $des_alternatif = [
                'id' => $alternatif->id_alternatif,
                'nama' => $alternatif->nama,
                'kriteria' => [],
            ];
            // Ambil bobot setiap kriteria
            foreach ($data['l_kriteria'] as $kriteria) {
                // Cek apakah kriteria memiliki referensi
                if (ReferensiKriteria::where('id_kriteria', $kriteria['id'])->exists()) {
                    $nilai_bobot = $alternatif->bobot()->where('id_kriteria', $kriteria['id'])->first()->bobot;
                    $nilai_bobot = ReferensiKriteria::where('id', $nilai_bobot)->first()->nama;
                } else {
                    $nilai_bobot = $alternatif->bobot()->where('id_kriteria', $kriteria['id'])->first()->bobot;
                }

                array_push($des_alternatif['kriteria'], [
                    'id' => $kriteria['id'],
                    'nama' => $kriteria['nama'],
                    'bobot' => $nilai_bobot,
                ]);
            }

            array_push($data['l_alternatif'], $des_alternatif);
        }

        return view('alternatif.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['l_grade_pendidikan'] = ReferensiKriteria::where('id_kriteria', 'K1572604752')->get();

        return view('alternatif.tambah', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi form
        $this->validate($request, [
            'nama' => 'required|max:60',
            'usia' => 'required|integer|min:20|max:50',
            'test' => 'required|integer|min:0|max:100',
            'pendidikan' => 'required|integer',
            'masa_kerja' => 'required|integer|min:2|max:20',
            'absensi' => 'required|integer|min:0|max:100',
        ]);

        $id_alternatif = 'A' . time();

        $alternatif = new Alternatif();
        $alternatif->id_alternatif = $id_alternatif;
        $alternatif->nama = $request->nama;
        $alternatif->username = Auth::id();
        $alternatif->save();

        $id_bobot = 'B' . time();
        // Bobot usia
        $usia = new Bobot();
        $usia->id_bobot = $id_bobot . '1';
        $usia->bobot = $request->usia;
        $usia->id_alternatif = $id_alternatif;
        $usia->id_kriteria = 'K1572604804';
        $usia->save();
        // Bobot test
        $test = new Bobot();
        $test->id_bobot = $id_bobot . '2';
        $test->bobot = $request->test;
        $test->id_alternatif = $id_alternatif;
        $test->id_kriteria = 'K1572604673';
        $test->save();
        // Bobot pendidikan
        $pendidikan = new Bobot();
        $pendidikan->id_bobot = $id_bobot . '3';
        $pendidikan->bobot = $request->pendidikan;
        $pendidikan->id_alternatif = $id_alternatif;
        $pendidikan->id_kriteria = 'K1572604752';
        $pendidikan->save();
        // Bobot masa kerja
        $masa_kerja = new Bobot();
        $masa_kerja->id_bobot = $id_bobot . '4';
        $masa_kerja->bobot = $request->masa_kerja;
        $masa_kerja->id_alternatif = $id_alternatif;
        $masa_kerja->id_kriteria = 'K1572604771';
        $masa_kerja->save();
        // Bobot absensi
        $absensi = new Bobot();
        $absensi->id_bobot = $id_bobot . '5';
        $absensi->bobot = $request->absensi;
        $absensi->id_alternatif = $id_alternatif;
        $absensi->id_kriteria = 'K1572604792';
        $absensi->save();

        return redirect('/alternatif')->with('sukses', 'Berhasil menambah alternatif baru');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function edit(Alternatif $alternatif)
    {
        $data['alternatif'] = [
            'id' => $alternatif->id_alternatif,
            'nama' => $alternatif->nama,
        ];
        foreach ($alternatif->kriteria as $kriteria) {
            $data['alternatif'][$kriteria->nama] = $kriteria->pivot->bobot;
        }

        $data['l_grade_pendidikan'] = ReferensiKriteria::where('id_kriteria', 'K1572604752')->get();

        return view('alternatif.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alternatif $alternatif)
    {
        // Validasi form
        $this->validate($request, [
            'nama' => 'required|max:60',
            'usia' => 'required|integer|min:20|max:50',
            'test' => 'required|integer|min:0|max:100',
            'pendidikan' => 'required|integer',
            'masa_kerja' => 'required|integer|min:2|max:20',
            'absensi' => 'required|integer|min:0|max:100',
        ]);

        $alternatif->nama = $request->nama;
        $alternatif->save();

        // Bobot usia
        $usia = Bobot::where('id_alternatif', $alternatif->id_alternatif)->where('id_kriteria', 'K1572604804')->first();
        $usia->bobot = $request->usia;
        $usia->save();
        // Bobot test
        $test = Bobot::where('id_alternatif', $alternatif->id_alternatif)->where('id_kriteria', 'K1572604673')->first();
        $test->bobot = $request->test;
        $test->save();
        // Bobot pendidikan
        $pendidikan = Bobot::where('id_alternatif', $alternatif->id_alternatif)->where('id_kriteria', 'K1572604752')->first();
        $pendidikan->bobot = $request->pendidikan;
        $pendidikan->save();
        // Bobot masa kerja
        $masa_kerja = Bobot::where('id_alternatif', $alternatif->id_alternatif)->where('id_kriteria', 'K1572604771')->first();
        $masa_kerja->bobot = $request->masa_kerja;
        $masa_kerja->save();
        // Bobot absensi
        $absensi = Bobot::where('id_alternatif', $alternatif->id_alternatif)->where('id_kriteria', 'K1572604792')->first();
        $absensi->bobot = $request->absensi;
        $absensi->save();

        return redirect('/alternatif')->with('sukses', 'Berhasil memperbarui data alternatif : <b>' . $alternatif->nama . '</b>');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Alternatif  $alternatif
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alternatif $alternatif)
    {
        $nama_alternatif = $alternatif->nama;
        foreach ($alternatif->bobot as $bobot) {
            $bobot->delete();
        }
        $alternatif->delete();

        return redirect('/alternatif')->with('sukses', 'Berhasil menghapus data alternatif : <b>' . $nama_alternatif . '</b>');
    }
}
