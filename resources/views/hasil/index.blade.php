@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('sukses'))
    <div class="row d-lg-flex justify-content-lg-center">
        <div class="col-lg-8">
            <div class="alert alert-success">
                {!! session('sukses') !!}
            </div>
        </div>
    </div>
    @endif

    <div class="row d-lg-flex justify-content-lg-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    Hasil
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-6">
                            Diperbarui pada: <br>
                            <small>
                                {{ $diperbarui }}
                            </small>
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">
                            <div>
                                <a href="{{ route('ranking.create') }}" class="btn btn-success" aria-label="Left Align">
                                    Perbarui Hasil
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        @foreach ($l_kriteria as $kriteria)
                                        <th>{{ $kriteria['nama'] }}</th>
                                        @endforeach
                                        <th>Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Bagian isi --}}
                                    @foreach ($l_alternatif as $alternatif)
                                    <tr>
                                        <td>{{ $alternatif['nama'] }}</td>
                                        @foreach ($alternatif['kriteria'] as $bobot_kriteria_alternatif)
                                        <td>{{ $bobot_kriteria_alternatif['bobot'] }}</td>
                                        @endforeach
                                        <td><strong class="text-primary">{{ $alternatif['skor'] }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection