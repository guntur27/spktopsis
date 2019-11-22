<?php

namespace App\Http\Controllers;

use App\Alternatif;
use App\Bobot;
use App\Hasil;
use App\Kriteria;
use App\Ranking;
use App\ReferensiKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
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
        $data['l_alternatif'] = [];

        foreach ($l_kriteria as $kriteria) {
            $des_kriteria = [
                'id' => $kriteria->id_kriteria,
                'nama' => $kriteria->nama,
            ];

            array_push($data['l_kriteria'], $des_kriteria);
        }

        $hasil = Hasil::where('created_at', Hasil::max('created_at'))->first();

        if ($hasil) {
            $data['diperbarui'] = $hasil->created_at;

            foreach ($hasil->ranking()->orderBy('nilai', 'desc')->get() as $ranking) {
                $des_alternatif = [
                    'id' => $ranking->id_alternatif,
                    'nama' => Alternatif::where('id_alternatif', $ranking->id_alternatif)->first()->nama,
                    'skor' => $ranking->nilai,
                    'kriteria' => [],
                ];

                // Ambil bobot setiap kriteria
                foreach ($data['l_kriteria'] as $kriteria) {
                    // Cek apakah kriteria memiliki referensi
                    if (ReferensiKriteria::where('id_kriteria', $kriteria['id'])->exists()) {
                        $nilai_bobot = Alternatif::where('id_alternatif', $ranking->id_alternatif)->first()->bobot()->where('id_kriteria', $kriteria['id'])->first()->bobot;
                        $nilai_bobot = ReferensiKriteria::where('id', $nilai_bobot)->first()->nama;
                    } else {
                        $nilai_bobot = Alternatif::where('id_alternatif', $ranking->id_alternatif)->first()->bobot()->where('id_kriteria', $kriteria['id'])->first()->bobot;
                    }

                    array_push($des_alternatif['kriteria'], [
                        'id' => $kriteria['id'],
                        'nama' => $kriteria['nama'],
                        'bobot' => $nilai_bobot,
                    ]);
                }

                array_push($data['l_alternatif'], $des_alternatif);
            }
        } else {
            $data['diperbarui'] = 'Belum pernah melakukan perhitungan';
        }

        return view('hasil.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil daftar id kriteria
        $l_kriteria = Kriteria::all();
        $data['l_kriteria'] = [];

        foreach ($l_kriteria as $kriteria) {
            $tmp = [
                'id' => $kriteria->id_kriteria,
                'bobot' => $kriteria->bobot,
                'atribut' => $kriteria->atribut,
            ];
            array_push($data['l_kriteria'], $tmp);
        }

        // Ambil daftar id alternatif
        $l_alternatif = Alternatif::all();
        $data['l_alternatif'] = [];

        foreach ($l_alternatif as $alternatif) {
            array_push($data['l_alternatif'], $alternatif->id_alternatif);
        }

        // 1. Buat matriks pertama
        $matriks = [];

        // Iterasi alternatif
        for ($i = 0; $i < count($data['l_alternatif']); $i++) {
            $tmp = [];
            // Iterasi keriteria
            for ($j = 0; $j < count($data['l_kriteria']); $j++) {
                $isi = Bobot::where('id_alternatif', $data['l_alternatif'][$i])->where('id_kriteria', $data['l_kriteria'][$j]['id'])->first();
                if (ReferensiKriteria::where('id_kriteria', $data['l_kriteria'][$j]['id'])->exists()) {
                    array_push($tmp, ReferensiKriteria::where('id', $isi->bobot)->first()->nilai);
                } else {
                    array_push($tmp, $isi->bobot);
                }
            }
            array_push($matriks, $tmp);
        }

        // 2. Normalisasi matriks
        $matriks_normalisasi = [];

        // Hitung sum kriteria
        $sum_kriteraia = [];
        for ($i = 0; $i < count($data['l_kriteria']); $i++) {
            $sum_kriteraia[$i] = 0;
            for ($j = 0; $j < count($data['l_alternatif']); $j++) {
                $sum_kriteraia[$i] += pow($matriks[$j][$i], 2);
            }
            $sum_kriteraia[$i] = round(sqrt($sum_kriteraia[$i]), 4);
        }

        for ($i = 0; $i < count($data['l_alternatif']); $i++) {
            for ($j = 0; $j < count($data['l_kriteria']); $j++) {
                $matriks_normalisasi[$i][$j] = round($matriks[$i][$j] / $sum_kriteraia[$j], 4);
            }
        }

        $matriks = $matriks_normalisasi;


        // 3. Matriks ternormalisasi terbobot
        $matriks_ternormalisasi_terbobot = [];

        for ($i = 0; $i < count($data['l_alternatif']); $i++) {
            for ($j = 0; $j < count($data['l_kriteria']); $j++) {
                $matriks_ternormalisasi_terbobot[$i][$j] = round($data['l_kriteria'][$j]['bobot'] * $matriks[$i][$j], 4);
            }
        }

        $matriks = $matriks_ternormalisasi_terbobot;

        // 4. Mencari solusi ideal positif
        $solusi_ideal_positif = [];

        for ($i = 0; $i < count($data['l_kriteria']); $i++) {
            $solusi_ideal_positif[$i] = $matriks[0][$i];
            if ($data['l_kriteria'][$i]['atribut'] === 1) {
                // Mencari nilai maksimal kriteria dari setiap alternatif
                for ($j = 0; $j < count($data['l_alternatif']); $j++) {
                    if ($matriks[$j][$i] > $solusi_ideal_positif[$i]) {
                        $solusi_ideal_positif[$i] = $matriks[$j][$i];
                    }
                }
            } else {
                // Mencari nilai minimal kriteria dari setiap alternatif
                for ($j = 0; $j < count($data['l_alternatif']); $j++) {
                    if ($matriks[$j][$i] < $solusi_ideal_positif[$i]) {
                        $solusi_ideal_positif[$i] = $matriks[$j][$i];
                    }
                }
            }
        }

        // 5. Mencari solusi ideal negatif
        $solusi_ideal_neagatif = [];

        for ($i = 0; $i < count($data['l_kriteria']); $i++) {
            $solusi_ideal_neagatif[$i] = $matriks[0][$i];
            if ($data['l_kriteria'][$i]['atribut'] === 0) {
                // Mencari nilai maksimal kriteria dari setiap alternatif
                for ($j = 0; $j < count($data['l_alternatif']); $j++) {
                    if ($matriks[$j][$i] > $solusi_ideal_neagatif[$i]) {
                        $solusi_ideal_neagatif[$i] = $matriks[$j][$i];
                    }
                }
            } else {
                // Mencari nilai minimal kriteria dari setiap alternatif
                for ($j = 0; $j < count($data['l_alternatif']); $j++) {
                    if ($matriks[$j][$i] < $solusi_ideal_neagatif[$i]) {
                        $solusi_ideal_neagatif[$i] = $matriks[$j][$i];
                    }
                }
            }
        }

        // 6. Mencari jarak solusi ideal positif
        $jarak_solusi_ideal_positif = [];

        for ($i = 0; $i < count($data['l_alternatif']); $i++) {
            $tmp_sum = 0;
            for ($j = 0; $j < count($data['l_kriteria']); $j++) {
                $tmp_sum += pow($solusi_ideal_positif[$j] - $matriks[$i][$j], 2);
            }
            $jarak_solusi_ideal_positif[$i] = round(sqrt($tmp_sum), 4);
        }

        // 7. Mencari jarak solusi ideal negarif
        $jarak_solusi_ideal_negatif = [];

        for ($i = 0; $i < count($data['l_alternatif']); $i++) {
            $tmp_sum = 0;
            for ($j = 0; $j < count($data['l_kriteria']); $j++) {
                $tmp_sum += pow($matriks[$i][$j] - $solusi_ideal_neagatif[$j], 2);
            }
            $jarak_solusi_ideal_negatif[$i] = round(sqrt($tmp_sum), 4);
        }

        // 8. Perankingan
        $ranking = [];

        for ($i = 0; $i < count($data['l_alternatif']); $i++) {
            $hasil = $jarak_solusi_ideal_negatif[$i] / ($jarak_solusi_ideal_negatif[$i] + $jarak_solusi_ideal_positif[$i]);
            $ranking[$i]['alternatif'] = $data['l_alternatif'][$i];
            $ranking[$i]['skor'] = round($hasil, 4);
        }

        $hasil = new Hasil();

        $id_hasil = 'H' . time();

        $hasil->id_hasil = $id_hasil;
        $hasil->username = Auth::id();
        $hasil->save();

        $id_ranking = 'RR' . time();
        for ($i = 0; $i < count($ranking); $i++) {
            $skor = new Ranking();
            $skor->id_ranking = $id_ranking . $i;
            $skor->nilai = $ranking[$i]['skor'];
            $skor->id_alternatif = $ranking[$i]['alternatif'];
            $skor->id_hasil = $id_hasil;
            $skor->save();
        }

        return redirect('/ranking')->with('sukses', 'Berhasil memperbarui hasil');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function show(Ranking $ranking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function edit(Ranking $ranking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ranking $ranking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ranking  $ranking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ranking $ranking)
    {
        //
    }
}
