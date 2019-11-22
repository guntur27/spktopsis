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
                <div class="card-header">Alternatif</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col d-flex justify-content-end">
                            <a href="{{ route('alternatif.create') }}" class="btn btn-success" aria-label="Left Align">
                                Tambah alternatif
                            </a>
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
                                        <th>#</th>
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
                                        <td>
                                            <a href="{{ route('alternatif.edit', ['alternatif' => $alternatif['id']]) }}"
                                                class="btn btn-sm btn-primary">
                                                Edit
                                            </a>
                                            <button
                                                data-href="{{ route('alternatif.destroy', ['alternatif' => $alternatif['id']]) }}"
                                                class="btn btn-sm btn-danger" id="btn-hapus" data-toggle="modal"
                                                data-target="#modal-hapus" data-id="{{ $alternatif['id'] }}"
                                                data-nama="{{ $alternatif['nama'] }}">
                                                Hapus
                                            </button>
                                        </td>
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

<!-- Modal -->
<div class="modal fade" id="modal-hapus" tabindex="-1" role="dialog" aria-labelledby="modal-hapus" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#modal-hapus').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var nama = button.data('nama')
        var link = button.data('href')

        var modal = $(this)
        modal.find('.modal-body').html(`
            Apakah anda ingin menghapus data ini: <br>
            ID : ` + id + ` <br>
            Nama : ` + nama + `
        `)
        modal.find('.modal-footer').html(`
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
            <form action="` + link + `" method="post">
                <input name="_method" type="hidden" value="DELETE">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-danger">Ya</button>
            </form>
        `)
    })
</script>
@endpush