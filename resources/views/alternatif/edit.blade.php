@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row d-lg-flex justify-content-lg-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    Edit Alternatif
                    <small class="text-primary">
                        {{ $alternatif['id'] }} ( {{ $alternatif['nama'] }} )
                    </small>
                </div>

                <div class="card-body">
                    <form action="{{ route('alternatif.update', ['alternatif' => $alternatif['id']]) }}" method="POST">
                        <input name="_method" type="hidden" value="PUT">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control {{ $errors->has('nama') ? ' is-invalid' : '' }}"
                                value="{{ old('nama') ? old('nama') : $alternatif['nama'] }}"
                                placeholder="Max. 60 karakter">

                            @if ($errors->has('nama'))
                            <div class="invalid-feedback">
                                {{ $errors->first('nama') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="usia">Usia</label>
                                <div class="input-group">
                                    <input type="number" name="usia" id="usia"
                                        class="form-control {{ $errors->has('usia') ? ' is-invalid' : '' }}"
                                        value="{{ old('usia') ? old('usia') : $alternatif['Usia'] }}"
                                        placeholder="20 - 50">
                                    <div class="input-group-append">
                                        <span class="input-group-text">tahun</span>
                                    </div>
                                    @if ($errors->has('usia'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('usia') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-4">
                                <label for="test">Niali Tes</label>
                                <input type="number" name="test" id="test"
                                    class="form-control {{ $errors->has('test') ? ' is-invalid' : '' }}"
                                    value="{{ old('test') ? old('test') : $alternatif['Tes'] }}" placeholder="0 - 100">
                                @if ($errors->has('test'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('test') }}
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-4">
                                <label for="pendidikan">Pendidikan Terakhir</label>
                                <select name="pendidikan" id="pendidikan"
                                    class="form-control {{ $errors->has('pendidikan') ? ' is-invalid' : '' }}">
                                    @foreach ($l_grade_pendidikan as $grade_pendidikan)
                                    <option value="{{ $grade_pendidikan->id }}"
                                        {{ old('pendidikan') ? (old('pendidikan')==$grade_pendidikan->id ? 'selected' : '') : ($alternatif['Pendidikan']==$grade_pendidikan->id ? 'selected' : '') }}>
                                        {{ $grade_pendidikan->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pendidikan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pendidikan') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="masa_kerja">Lama Kerja</label>
                                <div class="input-group">
                                    <input type="number" name="masa_kerja" id="masa_kerja"
                                        class="form-control {{ $errors->has('masa_kerja') ? ' is-invalid' : '' }}"
                                        value="{{ old('masa_kerja') ? old('masa_kerja') : $alternatif['Masa Kerja'] }}"
                                        placeholder="2 - 20">
                                    <div class="input-group-append">
                                        <span class="input-group-text">tahun</span>
                                    </div>
                                    @if ($errors->has('masa_kerja'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('masa_kerja') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label for="absensi">Absensi</label>
                                <div class="input-group">
                                    <input type="number" name="absensi" id="absensi"
                                        class="form-control {{ $errors->has('absensi') ? ' is-invalid' : '' }}"
                                        value="{{ old('absensi') ? old('absensi') : $alternatif['Absensi'] }}"
                                        placeholder="0 - 100">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @if ($errors->has('absensi'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('absensi') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection