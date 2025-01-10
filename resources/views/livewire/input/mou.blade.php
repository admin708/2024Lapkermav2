<div>
    <div>
        <div class="row">
            <!-- Basic -->
            <div class="col-12 {{ $idEdit == false ? 'd-none' : '' }} ">
                <button class="btn btn-primary my-3 form-control" wire:click="saveEdit('{{ $idEdit }}')">
                    <span wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm" role="status"
                        aria-hidden="true"></span>
                    <span wire:loading.remove wire:target="saveEdit">Simpan Perubahan</span>
                </button>
            </div>
            <div class="col-12 {{ $idEdit == true ? 'd-none' : '' }} ">
                <button class="btn btn-primary my-3 form-control" wire:click="save">
                    <span wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm" role="status"
                        aria-hidden="true"></span>
                    <span wire:loading.remove wire:target="save">Simpan</span>
                </button>
            </div>

            <div class="col-md-4">
                <div class="card mb-1">
                    <h5 class="card-header text-primary"><i class="bx bx-link me-3"></i>Jenis Kerjasama</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="col-auto my-2">
                            <label class="form-label">Pilih Jenis Kerjasama</label>
                            <div wire:loading wire:target="jenisKerjasamaField"
                                class="mx-1 spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <select required wire:model="jenisKerjasamaField" id="jk"
                                class="form-select form-select-sm
                            @error('jenisKerjasamaField') is-invalid @enderror ">
                                @foreach ($jenisKerjasama as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenisKerjasamaField')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="{{ $jenisKerjasamaField == 1 ? 'd-none' : '' }}">
                            <label class="form-label">Negara <i class="small text-danger">*</i> </label>
                            <select wire:model="negara" id="select2-negara"
                                class="form-select form-select-sm @error('negara') is-invalid @enderror">
                                <option></option>
                                @foreach ($negaraKerjasama as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('negara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="{{ $jenisKerjasamaField == 1 ? 'd-none' : '' }}">
                            <label class="form-label">Region</label>
                            <select wire:model="region" style="display: block"
                                class="form-select form-select-sm
                        @error('region') is-invalid @enderror">
                                <option></option>
                                @foreach ($regionKerjasama as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Tempat Pelaksana</label>
                            <input required wire:model.defer="tempat_pelaksanaan" type="text"
                                class="form-control form-control-sm @error('tempat_pelaksanaan') is-invalid @enderror">
                            @error('tempat_pelaksanaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <label class="form-label">Dokumen
                            <i class="small text-warning">* max 1 mb</i>
                        </label><br>
                        <input class="form-control form-control-sm @error('files') is-invalid @enderror" type="file"
                            wire:model="files" id="uploadFiles2" multiple accept=".pdf" {{-- onchange="checkFileUploadExt2(this);"  --}} />
                        @if ($idEdit)
                            <ul>
                                @foreach ($findDokumen as $item)
                                    <li>
                                        <a class="m-1" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasStart{{ $loop->iteration }}"
                                            aria-controls="offcanvasStart">
                                            Dokumen#{{ $loop->iteration }}
                                        </a>
                                    </li>
                                    <div class="offcanvas offcanvas-start" style="width: 50%" tabindex="-1"
                                        id="offcanvasStart{{ $loop->iteration }}" aria-labelledby="offcanvasStartLabel"
                                        aria-hidden="true" style="visibility: hidden;">
                                        <div class="offcanvas-header">
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                                            <p><iframe src="{{ asset('storage/DokumenMoU/' . $item->url) }}"
                                                    style="position:absolute;top:0;left:0;width:100%;height:100%;border:0"></iframe>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        @endif
                        @if ($showLoadFiles)
                            <div wire:loading wire:target="files">
                                @include('livewire._includeLoading')
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Merged -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <h5 class="card-header text-primary"><i class="bx bx-file me-3"></i>Jenis Dokumen Kerjasama</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="col-auto my-2">
                            <label class="form-label">Nomor Dok. Unhas</label>
                            <input wire:model.defer="nomor_unhas" type="text"
                                class="form-control form-control-sm 
                            {{ $nomorSistem == 1 ? 'd-none' : 'd-block' }}
                            @error('nomor_unhas') is-invalid @enderror"
                                {{ $idEdit ? 'disabled' : '' }}>
                            @error('nomor_unhas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div
                                class="form-check form-switch mb-2 {{ $idEdit ? 'd-none' : ($jenisKerjasamaField == 2 ? 'd-block' : 'd-none') }}">
                                <input class="form-check-input" type="checkbox" wire:model="nomorSistem">
                                <label class="form-check-label">
                                    <i class="small">* gunakan nomor sistem jika nomor dokumen tidak ada</i>
                                </label>
                                @error('nomorSistem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="{{ $jenisKerjasamaField == 2 ? 'd-none' : '' }}">
                            <label class="form-label">Nomor Dok. Mitra </label>
                            <input required wire:model.defer="nomor_mitra" type="text"
                                class="form-control form-control-sm 
                            @error('nomor_mitra') is-invalid @enderror">
                            @error('nomor_mitra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Judul Kerjasama</label>
                            <input required wire:model.defer="judul_kerjasama" type="text"
                                class="form-control form-control-sm @error('judul_kerjasama') is-invalid @enderror">
                            @error('judul_kerjasama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Deskripsi <label>
                                    <i class="small text-danger">
                                        Ringkasan singkat terkait cakupan atau kegiatan kerja
                                    </i>
                                </label>
                                <textarea required wire:model.defer="deskripsi"
                                    class="form-control form-control-sm @error('deskripsi') is-invalid @enderror" cols="30" rows="5"></textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sizing -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <h5 class="card-header text-primary"><i class="bx bx-calendar me-3"></i>Masa Berlaku</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="col-auto my-2">
                            <label class="form-label">Tanggal TTD <i class="text-danger">*</i></label>
                            <input required wire:model.defer="tanggal_ttd" type="date"
                                class="form-control form-control-sm @error('tanggal_ttd') is-invalid @enderror">
                            @error('tanggal_ttd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-auto my-2">
                            <label class="form-label">Tanggal Awal <i class="text-danger">*</i></label>
                            <input required wire:model.defer="tanggal_awal" type="date"
                                class="form-control form-control-sm @error('tanggal_awal') is-invalid @enderror">
                            @error('tanggal_awal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-auto my-2">
                            <label class="form-label">Tanggal Berakhir <i class="text-danger">*</i></label>
                            <input required wire:model.defer="tanggal_berakhir" type="date"
                                class="form-control form-control-sm @error('tanggal_berakhir') is-invalid @enderror">
                            @error('tanggal_berakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-auto my-2">
                            <label class="form-label">Status <i class="text-danger">*</i></label>
                            <select required wire:model.defer="status_kerjasama"
                                class="form-select form-select-sm @error('status_kerjasama') is-invalid @enderror">
                                <option></option>
                                @foreach ($statusKerjasama as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('status_kerjasama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-auto my-2">
                            <label class="form-label">Jangka Waktu <i class="small">(Tahun)</i> <i
                                    class="text-danger">*</i></label>
                            <select required wire:model.defer="jangka_waktu"
                                class="form-control form-control-sm @error('jangka_waktu') is-invalid @enderror">
                                <option value="">Pilih Jangka Waktu</option>
                                @foreach (range(1, 5) as $year)
                                    <option value="{{ $year }}">{{ $year }} Tahun</option>
                                @endforeach
                            </select>
                            @error('jangka_waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 col-lg-7">
                {{-- <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header text-primary"><i class="bx bx-link me-3"></i>Pihak 1</h5>
                    <div class="card-body mt-0">
                        <div class="row my-2">
                            <label class="mr-sm-2 mt-2 "><strong>Penggiat Kerjasama</strong> </label>
                            <div class="col-sm-12 col-lg-5 my-2">
                                <label class="mr-sm-2">Status</label>
                                <select class="form-select form-select-sm mr-sm-2" disabled>
                                    <option value="1">Perguruan Tinggi Negeri</option>
                                </select>
                            </div>                            
                            <div class="col-sm-12 col-lg-7 my-2">
                                <label class="mr-sm-2">Nama Pejabat </label>
                                <input type="text" class="form-control form-control-sm" value="Universitas Hasanuddin"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-auto my-2">
                            <div>
                                <label class="mr-sm-2">Fakultas</label>
                                <select wire:model="fakultas_pihak.0"
                                    class="form-select form-select-sm mr-sm-2 @error('fakultas_pihak.0') is-invalid @enderror">
                                    <option></option>
                                    @foreach ($fakultas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-auto my-2">
                            <label class="mr-sm-2">Alamat Instansi</label>
                            <input wire:model.defer="alamat_pihak.0" type="text"
                                class="form-control form-control-sm @error('alamat_pihak.0') is-invalid @enderror">
                        </div>
                        <div class="col-auto my-2">
                            <label class="mr-sm-2 mt-2 "><strong>Penandatangan</strong></label>&nbsp;
                            <i class="small text-danger">
                                *Pejabat yang menandatangani dokumen
                            </i>
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <label class="mr-sm-2">Nama</label>
                                    <input wire:model.defer="nama_pejabat_pihak.0" type="text"
                                        class="form-control form-control-sm @error('nama_pejabat_pihak.0') is-invalid @enderror">
                                </div>
                                <div class="col-sm-12 col-lg-6">

                                    <label class="mr-sm-2">Jabatan</label>
                                    <input wire:model.defer="jabatan_pejabat_pihak.0" type="text"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-auto my-2">
                            <label class="mr-sm-2 mt-2 "><strong>Penanggung Jawab</strong> </label>
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <label class="mr-sm-2">Nama</label>
                                    <input required wire:model.defer="pj_pihak.0" type="text"
                                        class="form-control form-control-sm @error('pj_pihak.0') is-invalid @enderror">
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <label class="mr-sm-2">Jabatan</label>
                                    <input wire:model.defer="jabatan_pj_pihak.0" type="text"
                                        class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <label class="mr-sm-2 mt-1">Email</label>
                                    <input wire:model.defer="email_pj_pihak.0" type="email"
                                        class="form-control form-control-sm @error('email_pj_pihak.0') is-invalid @enderror">
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <label class="mr-sm-2 mt-1">No. HP</label>
                                    <input wire:model.defer="hp_pj_pihak.0" type="number"
                                        class="form-control form-control-sm @error('hp_pj_pihak.0') is-invalid @enderror">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
                @foreach ($inputs as $key => $value)
                    <div class="col-md-12">
                        <div class="card mb-4" style="display: {{ $value <= $arrayJawaban ? 'block' : 'none' }}">
                            <h5 class="card-header text-primary"><i class="bx bx-link me-3"></i>Pihak
                                {{ $value + 1 }}
                                @if ($arrayJawaban != 0 && $arrayJawaban == $value)
                                    <button type="button" wire:click="takeArray" class="btn-sm btn"
                                        style="float: right"><i class="bx bx-layer-plus h3 text-primary"></i></button>
                                @endif
                                @if ($arrayJawaban != 1 && $arrayJawaban == $value)
                                    <button type="button" wire:click="minArrayPihak({{ $key }})"
                                        class="btn-sm btn" style="float: right"><i
                                            class="bx bx-layer-minus h3 text-danger"></i></button>
                                @endif
                            </h5>
                            <div class="card-body mt-0">
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2 mt-2 "><strong>Penggiat Kerjasama</strong> </label>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-5 my-2">
                                            <label class="mr-sm-2">Status <i class="text-danger">*</i></label>
                                            <div wire:loading wire:target="status.{{ $value }}"
                                                class="mx-1 spinner-border spinner-border-sm text-primary"
                                                role="status">
                                                @error('status.{{ $value }}')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <select wire:model="status.{{ $value }}"
                                                class="form-select form-select-sm mr-sm-2 @error('status.' . $value) is-invalid @enderror">
                                                <option></option>
                                                <option value="1">Perguruan Tinggi Negeri</option>
                                                <option value="2">Perguruan Tinggi Swasta</option>
                                                <option value="4">Perguruan Tinggi Luar Negeri</option>
                                                <option value="3">Mitra</option>
                                            </select>
                                            @error('status.' . $value)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 col-lg-7 my-2">
                                            <label class="mr-sm-2">Instansi / Universitas <i
                                                    class="small text-danger">*</i>
                                            </label>

                                            <div wire:loading wire:target="nama_pihak.{{ $key }}"
                                                class="mx-1 spinner-border spinner-border-sm text-primary"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>

                                            <div class="btn-group col-12">
                                                <div class="input-group input-group-sm" data-bs-display="static"
                                                    aria-haspopup="true" aria-expanded="true">
                                                    <input placeholder="Ketik Untuk Mencari"
                                                        wire:model.debounce.500ms="nama_pihak.{{ $key }}"
                                                        type="text"
                                                        class="form-control form-control-sm @error('nama_pihak.' . $value) is-invalid @enderror">
                                                    <span onclick="addInstansi()"
                                                        class="input-group-text cursor-pointer d-block"><i
                                                            class="bx bx-plus"></i></span>
                                                    @error('nama_pihak.' . $value)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start {{ isset($searchInstansiList[$key]) && count($searchInstansiList[$key]) > 0 ? 'show' : '' }}"
                                                    data-bs-popper="static">
                                                    @foreach ($searchInstansiList[$key] ?? [] as $instansi)
                                                        {{-- {{ dd($instansi) }} --}}
                                                        <li>
                                                            <button
                                                                wire:click="selectInstansi({{ $key }}, {{ $instansi['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                class="small dropdown-item" type="button">
                                                                {{ strtoupper($instansi['name'] ?? 'N/A') }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-5 my-2 {{ optional($status)[$value] == 1 ? 'd-block' : (optional($status)[$value] == 4 ? 'd-block' : '') }}"
                                            style="display: none">
                                            <label class="mr-sm-2">PTQS 100<i class="text-danger">*</i></label>
                                            <select wire:model="ptqs.{{ $value }}"
                                                class="@error('ptqs.' . $value) is-invalid @enderror form-select form-select-sm mr-sm-2">
                                                <option value=""></option>
                                                <option value="1">Ya</option>
                                                <option value="2">Tidak</option>
                                            </select>
                                            @error('ptqs.' . $value)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 col-lg-7 my-2 {{ optional($status)[$value] == 1 ? 'd-block' : (optional($status)[$value] == 4 ? 'd-block' : '') }}"
                                            style="display: none">
                                            <label class="mr-sm-2">Cek Ranking</label>
                                            <a href="https://www.topuniversities.com/subject-rankings/2022"
                                                target="blank" class="form-control btn btn-sm btn-secondary">click</a>
                                        </div>

                                        <div class="col-sm-12 col-lg-5 my-2 {{ optional($status)[$value] == 3 ? 'd-block' : '' }}"
                                            style="display: none">
                                            <select wire:model="badanKemitraan.{{ $value }}"
                                                class="form-select form-select-sm mr-sm-2 @error('badanKemitraan.' . $value) is-invalid @enderror">
                                                <option value=""></option>
                                                @foreach ($badanKemitraanOptions as $option)
                                                    <option value="{{ $option->id }}">{{ $option->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('badanKemitraan.{{ $value }}')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div
                                            class="col-sm-12 col-lg-5 my-2 {{ optional($status)[$value] == 3 && optional($badanKemitraan)[$value] == 99 ? 'd-block' : 'd-none' }}">
                                            <input wire:model="lainnya.{{ $value }}" type="text"
                                                placeholder="sebutkan"
                                                class="form-control form-control-sm mr-sm-2 @error('lainnya.' . $value) is-invalid @enderror">
                                            @error('lainnya.{{ $value }}')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-auto my-2">
                                        <label class="mr-sm-2">Universitas/Fakultas</label>
                                        <select wire:model="fakultas_pihak.{{ $value }}"
                                            class="form-select form-select-sm mr-sm-2 @error('fakultas_pihak.' . $value) is-invalid @enderror"
                                            id="status{{ $value }}">
                                            <option></option>
                                            @foreach ($fakultas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_fakultas }}
                                                </option>
                                            @endforeach
                                            @error('fakultas_pihak.' . $value)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2">Alamat Instansi</label>
                                    <input wire:model.defer="alamat_pihak.{{ $value }}" type="text"
                                        class="form-control form-control-sm @error('alamat_pihak.' . $value) is-invalid @enderror">
                                    @error('alamat_pihak.' . $value)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2">Negara Instansi</label>
                                    <select wire:model="negara_pihak.{{ $value }}"
                                        class="form-select form-select-sm
                                            @error('negara_pihak.' . $value) is-invalid @enderror">
                                        <option></option>
                                        @foreach ($negaraKerjasama as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2">Cek Koordinat / Coordinates Check</label>
                                    <a href="https://www.google.com/maps?q={{ urlencode($nama_pihak[$value] ?? '') }}"
                                        target="blank" class="form-control btn btn-sm btn-secondary">click</a>
                                </div>
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2">Koordinat Instansi / Institute
                                        Coordinates</label>
                                    <input required wire:model="koordinat_pihak.{{ $value }}" type="text"
                                        class="form-control form-control-sm @error('koordinat_pihak.' . $value) is-invalid @enderror">
                                    @error('koordinat_pihak.' . $value)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2 mt-2 "><strong>Penandatangan</strong></label>&nbsp;
                                    <i class="small text-danger">
                                        *Pejabat yang menandatangani dokumen
                                    </i>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <label class="mr-sm-2">Nama<i class="text-danger">*</i></label>
                                            <div wire:loading wire:target="nama_pejabat_pihak.{{ $key }}"
                                                class="mx-1 spinner-border spinner-border-sm text-primary"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <div class="btn-group col-12">
                                                <div class="input-group input-group-sm @error('nama_pejabat_pihak.' . $value) is-invalid @enderror"
                                                    data-bs-display="static" aria-haspopup="true"
                                                    aria-expanded="true">
                                                    <input placeholder="Ketik Untuk Mencari"
                                                        wire:model.debounce.500ms="nama_pejabat_pihak.{{ $key }}"
                                                        type="text"
                                                        class="form-control form-control-sm @error('nama_pejabat_pihak.' . $value) is-invalid @enderror">
                                                    @error('nama_pejabat_pihak.{{ $value }}')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>


                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start {{ !empty($searchPejabatList[$key]) ? 'show' : '' }}"
                                                    data-bs-popper="static">
                                                    @foreach ($searchPejabatList[$key] ?? [] as $pejabat)
                                                        <li>
                                                            <button
                                                                wire:click="updatePejabatPihak({{ $key }}, {{ $pejabat['id'] }})"
                                                                class="small dropdown-item" type="button">
                                                                {{ strtoupper($pejabat['nama']) }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-6">

                                            <label class="mr-sm-2">Jabatan<i class="text-danger">*</i></label>
                                            <input wire:model.defer="jabatan_pejabat_pihak.{{ $value }}"
                                                type="text"
                                                class="form-control form-control-sm @error('jabatan_pejabat_pihak.' . $value) is-invalid @enderror">
                                            @error('jabatan_pejabat_pihak.' . $value)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto my-2">
                                    <label class="mr-sm-2 mt-2 "><strong>Penanggung Jawab</strong> </label>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <label class="mr-sm-2">Nama<i class="text-danger">*</i></label>
                                            <div wire:loading wire:target="pj_pihak.{{ $key }}"
                                                class="mx-1 spinner-border spinner-border-sm text-primary"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <div class="btn-group col-12">
                                                <div class="input-group input-group-sm" data-bs-display="static"
                                                    aria-haspopup="true" aria-expanded="true">
                                                    <input placeholder="Ketik Untuk Mencari"
                                                        wire:model.debounce.500ms="pj_pihak.{{ $key }}"
                                                        type="text"
                                                        class="form-control form-control-sm @error('pj_pihak.' . $value) is-invalid @enderror">
                                                    @error('pj_pihak.{{ $value }}')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start {{ isset($searchPenanggungJawab[$key]) && count($searchPenanggungJawab[$key]) > 0 ? 'show' : '' }}"
                                                    data-bs-popper="static">
                                                    @foreach ($searchPenanggungJawab[$key] ?? [] as $pj)
                                                        <li>
                                                            <button
                                                                wire:click="setPJData({{ $key }}, {{ $pj['id'] }} )"
                                                                class="small dropdown-item"
                                                                type="button">{{ strtoupper($pj['name']) }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-lg-6">
                                            <label class="mr-sm-2">Jabatan<i class="text-danger">*</i></label>
                                            <input wire:model.defer="jabatan_pj_pihak.{{ $value }}"
                                                type="text"
                                                class="form-control form-control-sm @error('jabatan_pj_pihak.' . $value) is-invalid @enderror">
                                            @error('jabatan_pj_pihak.{{ $value }}')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-6">
                                            <label class="mr-sm-2 mt-1">Email <i class="text-danger">*</i></label>
                                            <input wire:model.defer="email_pj_pihak.{{ $value }}"
                                                type="email"
                                                class="form-control form-control-sm @error('email_pj_pihak.' . $value) is-invalid @enderror">
                                            @error('email_pj_pihak.{{ $value }}')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-lg-6">
                                            <label class="mr-sm-2 mt-1">No. HP <i class="text-danger">*</i></label>
                                            <input wire:model.defer="hp_pj_pihak.{{ $value }}" type="text"
                                                onkeypress="return /[0-9()+\-]/.test(event.key)"
                                                class="form-control form-control-sm @error('hp_pj_pihak.' . $value) is-invalid @enderror">
                                            @error('hp_pj_pihak.{{ $value }}')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-sm-12 col-lg-5">
                <div class="card mb-4">
                    <h5 class="card-header text-primary"><i class="bx bx-unite me-3"></i>Bentuk Kegiatan
                        <div wire:loading wire:target="bentukKegiatan"
                            class="mx-1 spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </h5>
                    <div class="card-body">
                        <div class="col-auto my-2">
                            <select wire:model="bentukKegiatan"
                                class="form-select form-select-sm mr-sm-2 @error('arrayBentukKegiatan') is-invalid @enderror">
                                <option value="0">Pilih Bentuk Kegiatan</option>
                                @foreach ($getBentukKegiatan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('bentukKegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @forelse ($arrayBentukKegiatan as $key => $item)
                            <div class="card my-1">
                                <label class="m-1">
                                    <button type="button" wire:click="minArrayBentuk({{ $key }})"
                                        class="btn-sm btn text-danger" style="float: right"><i
                                            class="bx bx-layer-minus"></i></button>
                                </label>
                                <label class="mx-3"><i
                                        class="bx bx-bullseye me-2"></i>{{ $getBentukKegiatan->find($item)->nama }}
                                </label>

                                <div class="card-body">
                                    <div class="col-auto my-1">
                                        <div class="col-sm-12">
                                            <label class="@error('nilai_kontrak.' . $key) text-danger @enderror">Nilai
                                                kontrak</label>
                                            <div class="text-muted small m-b-xs mb-1">Nominal nilai kontrak
                                                proposal</div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text">Rp.</span>
                                                <input type="text"
                                                    wire:model.lazy="nilai_kontrak.{{ $key }}"
                                                    class="form-control form-control-sm" id="{{ $key }}"
                                                    placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto my-1">
                                        <div class="col-sm-12"><label>Luaran</label></div>
                                        <div class="col-sm-12">
                                            <div class="input-group input-group-merge">
                                                <span
                                                    class="input-group-text @error('volume_luaran.' . $key) text-danger @enderror">Volume</span>
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model.lazy="volume_luaran.{{ $key }}"
                                                    placeholder="0">
                                                <span
                                                    class="input-group-text @error('volume_satuan.' . $key) text-danger @enderror">@</span>
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model.lazy="volume_satuan.{{ $key }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto my-1">
                                        <div class="col-sm-12">
                                            <label
                                                class="@error('keterangan.' . $key) text-danger @enderror">Keterangan</label>
                                            <div class="text-muted small m-b-xs mb-1">Ringkasan luaran dari
                                                kegiatan</div>
                                        </div>
                                        <div class="col-sm-12">
                                            <textarea wire:model.lazy="keterangan.{{ $key }}" rows="3" class="form-control form-control-sm"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="col-sm-12">
                                            <label
                                                class="@error('arraySasaran.' . $key) text-danger @enderror"s>Sasaran</label>
                                            <div wire:loading wire:target="arraySasaran.{{ $key }}"
                                                class="mx-1 spinner-border spinner-border-sm text-primary"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <select wire:model="arraySasaran.{{ $key }}"
                                                class="form-select form-select-sm" aria-hidden="true">
                                                <option></option>
                                                @foreach ($getSasaranKegiatan as $items)
                                                    <option value="{{ $items->id }}">{{ $items->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="col-sm-12">
                                            <label
                                                class="@error('arrayKinerja.' . $key) text-danger @enderror">Indikator
                                                Kinerja</label>
                                        </div>
                                        <div class="col-sm-12">
                                            <select class="form-select form-select-sm" aria-hidden="true"
                                                wire:model="arrayKinerja.{{ $key }}">
                                                <option></option>
                                                @foreach ($getIndikatorKinerja->where('id_sasaran_kegiatan', $arraySasaran[$key] ?? null) as $itemz)
                                                    <option value="{{ $itemz->id }}">{{ $itemz->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                var tanpa_rupiah = document.getElementById('{{ $key }}');
                                tanpa_rupiah.addEventListener('keyup', function(e) {
                                    tanpa_rupiah.value = formatRupiah(this.value);
                                });
                            </script>
                        @empty
                            <label class="my-2 text-warning">Belum Memilih Bentuk Kegiatan</label>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div>
            <label class="m-3">
                <i class="small">
                    last update by : {{ $upBy ?? '' }}
                </i>
            </label>
        </div>
    </div>
    @push('custom-scripts')
        <script>
            /* Fungsi */
            function formatRupiah(angka) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            function addInstansi() {
                (async () => {
                    const {
                        value: instansi
                    } = await Swal.fire({
                        input: 'text',
                        inputLabel: 'Tambah Nama Instansi',
                        showCancelButton: true
                    })

                    if (instansi) {
                        Livewire.emit('addInstansi', instansi)
                    } else {
                        Swal.fire(`Data Tidak Valid`)
                    }
                })()
            }
        </script>
    @endpush
</div>