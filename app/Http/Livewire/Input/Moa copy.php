<?php

namespace App\Http\Livewire\Input;

use Livewire\Component;
use App\Models\DataMoaDokumen;
use App\Models\DataIaDokumen;
use App\Models\DataMoaPenggiat;
use App\Models\DataIaPenggiat;
use App\Models\DataMoaBentukKegiatanKerjasama;
use App\Models\DataIaBentukKegiatanKerjasama;
use App\Models\DataMoa;
use App\Models\DataIa;
use App\Models\DataMou;
use Livewire\WithFileUploads;
use App\Models\JenisDokumenKerjasama;
use App\Models\JenisKerjasama;
use App\Models\LapkermaRefBentukKegiatan;
use App\Models\LapkermaRefSasaranKegiatan;
use App\Models\LapkermaRefIndikatorKinerja;
use App\Models\Lapkerma;
use App\Models\Region;
use App\Models\Negara;
use App\Models\Prodi;
use App\Models\Intansi;
use App\Models\ProdiMitra;
use App\Models\Fakultas;
use App\Models\FakultasPihak;
use App\Models\IaPenggiat;
use App\Models\Instansi;
use App\Models\KegiatanKerjasama;
use App\Models\MoaPenggiat;
use App\Models\Pejabat;
use App\Models\PenanggungJawab;
use App\Models\ReferensiSumberDanaLapkerma;
use App\Models\PenggiatKerjasama;
use App\Models\ReferensiBadanKemitraan;
use App\Models\Sdgs;
use App\Models\StatusKerjasama;
use Illuminate\Database\QueryException as ERROR;
use Illuminate\Support\Facades\DB;
// use DB;
use Hamcrest\Type\IsNumeric;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Support\Str;

class Moa extends Component
{
    use WithFileUploads;
    public $nama_fakultas = [], $lockFakultas = [], $arrayFakultas = [], $changeJenis, $disMoA = false;
    public $arrayMitra = [], $prodiPihak = [], $getFakultasMitras, $searchFakultasMitra = [], $fakultasMitra = [];
    public $bentukKegiatan, $renderSwitch, $badanKemitraan = [], $lainnya = [], $ptqs = [], $idEdit, $findDokumen, $showProdiDefault = [];
    public $arrayProdi = [], $arrayNamaProdi = [], $name = [], $showLoadFiles = false, $jenisKerjasamaField;
    public $ottPlatform, $files = [], $arrayJawaban = 1, $pin, $status = [], $inputs = [0, 1], $lockInstansi = [];
    public $arrayBentukKegiatan = [], $arraySasaran = [], $arrayKinerja = [], $arraySdgs = [], $keterangan, $volume_luaran, $volume_satuan, $nilai_kontrak;

    public $tanggal_ttd, $jenis_kerjasama, $tingkat, $negara, $region, $kegiatan_kerjasama, $tempat_pelaksanaan, $status_kerjasama;
    public $tanggal_awal, $tanggal_berakhir, $jangka_waktu, $jenis_dokumen_kerjasama, $dasar_dokumen_kerjasama, $cek_dasar_dokumen_kerjasama;
    public $nomor_unhas, $nomor_mitra, $judul_kerjasama, $deskripsi, $anggaran, $sumber_dana;

    public $nama_pihak = [], $fakultas_pihak = [], $alamat_pihak = [], $koordinat_pihak = [], $negara_pihak = [];
    public $nama_pejabat_pihak = [], $jabatan_pejabat_pihak = [];
    public $pj_pihak = [], $jabatan_pj_pihak = [], $email_pj_pihak = [], $hp_pj_pihak = [];
    public $jenisKerjasama, $negaraKerjasama, $regionKerjasama, $kegiatanKerjasama, $statusKerjasama, $dasarDokKerjasama2;
    public $stat1, $stat2, $stat3, $stat4, $stat5, $stat6, $stat7, $stat8, $searchProdiMitra, $fakultas, $prodiMitra = [], $prodiAll, $dasarDokKerjasama, $sumberDana;

    public $searchInstansiList = [], $searchPejabatList = [], $searchPenanggungJawab = [], $searchBadanKemitraan;
    public $badanKemitraanOptions;
    public $idInstansi = [], $idPejabat = [], $idPJ = [];

    public $getBentukKegiatan, $getIndikatorKinerja, $getSasaranKegiatan, $jenisDokKerjasama, $getProdiMitras, $getSdgs, $sdgs;
    
    protected $listeners = [
        'successMe' => 'takeSuccess',
        'errorMe' => 'takeError',
        'getEditData' => 'showEditData',
        'addProdiMitra' => 'addProdiMitra',
        'addInstansi' => 'addInstansi',
        'addFakultasMitra' => 'addFakultasMitra',
    ];

    public function addProdiMitra($value)
    {
        $create = ProdiMitra::firstOrCreate([
            'nama_resmi' => $value
        ]);
        if ($create->wasRecentlyCreated) {
            $this->emit('alerts', ['pesan' => 'Berhasil ditambahkan', 'icon' => 'success']);
        } else {
            $this->emit('alerts', ['pesan' => 'Data Duplikat', 'icon' => 'error']);
        };
    }


    public function setJenis()
    {

        $this->jenis_dokumen_kerjasama = $this->changeJenis;
    }

    // public function updatedBadanKemitraan($value, $key)
    // {
    //     dd($this->badanKemitraan);
    // }

    public function updatedJenisDokumenKerjasama()
    {
        $this->reset('dasar_dokumen_kerjasama');
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedRegion()
    {
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedDasarDokumenKerjasama()
    {
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function addFakultasMitra($value)
    {
        $create = FakultasPihak::firstOrCreate([
            'nama_fakultas' => $value
        ]);
        if ($create->wasRecentlyCreated) {
            $this->emit('alerts', ['pesan' => 'Berhasil ditambahkan', 'icon' => 'success']);
        } else {
            $this->emit('alerts', ['pesan' => 'Data Duplikat', 'icon' => 'error']);
        };
    }

    public function addInstansi($value)
    {
        // if (strtolower($value) == 'unhas') {
        //     $this->emit('alerts', ['pesan' => 'Data Tidak Dapat Ditambahkan', 'icon'=>'error'] );
        // } else {
        //     if ($status != 0) {
        //         $create = Intansi::firstOrCreate([
        //             'nama_instansi' => $value
        //         ],[
        //             'status' => $status
        //         ]);
        //         if ($create->wasRecentlyCreated)
        //         {
        //         $this->emit('alerts', ['pesan' => 'Berhasil ditambahkan', 'icon'=>'success'] );
        //         }else{
        //         $this->emit('alerts', ['pesan' => 'Data Duplikat', 'icon'=>'error'] );
        //         };
        //     } else {
        //         $this->emit('alerts', ['pesan' => 'Gagal Silahkan Pilih Status', 'icon'=>'error'] );
        //     }
        // }

        // if ($status != 0) {
        //     $instansi = Str::lower($value);

        //     if (strpos($instansi, 'unhas') !== false) {
        //         // Jika $nama mengandung kata "Unhas"
        //         $this->emit('alerts', ['pesan' => 'Gunakan Nama Uiversitas Hasanuddin', 'icon' => 'error']);
        //     } else {
        //         $create = Intansi::firstOrCreate([
        //             'nama_instansi' => $value
        //         ], [
        //             'status' => $status
        //         ]);

        //         if ($create->wasRecentlyCreated) {
        //             $this->emit('alerts', ['pesan' => 'Berhasil ditambahkan', 'icon' => 'success']);
        //         } else {
        //             $this->emit('alerts', ['pesan' => 'Data Duplikat', 'icon' => 'error']);
        //         };
        //     }
        // } else {
        //     $this->emit('alerts', ['pesan' => 'Gagal Silahkan Pilih Status', 'icon' => 'error']);
        // }

        if($value){
            $instansi = Str::lower($value);
            if(strpos($instansi, 'unhas')){
                $this->emit('alerts', ['pesan' => 'Data Duplikat', 'icon' => 'error']);
            } else {
                $create = Instansi::firstOrCreate([
                    'name' => $value
                ], [
                    'address' => '',
                    'negara_id' => null,
                    'coordinates' => '',
                    'ptqs' =>  null,
                    'status' =>  null,
                    'badan_kemitraan' => null,
                ]);

                if ($create->wasRecentlyCreated) {
                    $this->emit('alerts', ['pesan' => 'Berhasil ditambahkan', 'icon' => 'success']);
                } else {
                    $this->emit('alerts', ['pesan' => 'Data Duplikat', 'icon' => 'error']);
                };
            }
        }
    }

    public function updatedNamaPihak($value, $key)
{
    // Normalize the input value
    $value = trim(strtolower($value)) === 'unhas' ? 'Universitas Hasanuddin' : trim($value);
    // Prevent fetching if the input is empty
    if (empty($value)) {
        $this->searchInstansiList[$key] = [];
        $this->idInstansi[$key] = null;
        return;
    }

    // Fetch matching instansi from the model
    $modelInstansis = new Instansi();
    $this->searchInstansiList[$key] = $modelInstansis->getInstansis($value);
    $this->idInstansi[$key] = null;
}


public function selectInstansi($key, $id)
{
    $instansi = Instansi::find($id);

    if (!$instansi) {
        // Handle the case where instansi is not found
        session()->flash('error', 'Selected instansi not found.');
        return;
    }

    // Assign values with safe defaults
    $this->nama_pihak[$key] = $instansi->name;
    $this->alamat_pihak[$key] = $instansi->address ?? '';
    $this->negara_pihak[$key] = $instansi->negara_id ?? 103;
    $this->koordinat_pihak[$key] = $instansi->coordinates ?? '';
    $this->ptqs[$key] = $instansi->ptqs ?? null;
    $this->status[$key] = $instansi->status ?? null;
    $this->badanKemitraan[$key] = $instansi->badan_kemitraan ?? null;
    $this->idInstansi[$key] = $instansi->id;

    // Additional logic for user roles
    $user = auth()->user();
    if ($user->role_id !== 1 && $instansi->name === 'Universitas Hasanuddin') {
        $this->fakultas_pihak[$key] = $user->fakultas_id;

        if ($user->role_id !== 4) {
            $this->prodiPihak[$key] = [$user->prodi_id];
            $this->arrayNamaProdi[$key] = [$user->prodi->nama_resmi ?? ''];
        }
    }

    // Clear the search results after selection
    $this->searchInstansiList[$key] = [];
}


public function updatedNamaPejabatPihak($value, $key)
{
    if (!empty($this->nama_pejabat_pihak[$key])) {
        $this->searchPejabatList[$key] = Pejabat::select('id', 'nama')
            ->where('nama', 'LIKE', '%' . $value . '%')
            ->limit(10) // Limit the number of results
            ->get()
            ->toArray();
    } else {
        $this->searchPejabatList[$key] = [];
    }
    $this->idPejabat[$key] = null;
}

    public function updatePejabatPihak($key, $id)
    {
        $pejabat = Pejabat::find($id);
        $this->nama_pejabat_pihak[$key] = $pejabat->nama;
        $this->jabatan_pejabat_pihak[$key] = $pejabat->jabatan;
        $this->idPejabat[$key] = $id;
        $this->searchPejabatList[$key] = [];
    }

    public function updatedPjPihak($value, $key)
    {
        if (!empty($this->pj_pihak[$key])) {
            $pjModel = new PenanggungJawab();
            $result = $pjModel->getPj($value);
            $this->searchPenanggungJawab[$key] = $result;
        } else {
            $this->searchPenanggungJawab[$key] = [];
        }
        $this->idPJ[$key] = null;
    }

    public function setPJData($key, $id)
    {
        $pj = PenanggungJawab::find($id);
        $this->pj_pihak[$key] = $pj->name;
        $this->jabatan_pj_pihak[$key] = $pj->designation;
        $this->hp_pj_pihak[$key] = $pj->phone_number;
        $this->email_pj_pihak[$key] = $pj->email;
        $this->idPJ[$key] = $id;
        $this->searchPenanggungJawab = [];
    }

    public function mount($id = null, $val = null)
    {
        // dd($val);
        // $this->renderSwitch = $val;
        if ($val == 3) {
            $this->jenis_dokumen_kerjasama = $val;
        } else {
            $this->cek_dasar_dokumen_kerjasama = $id;
        }

        $this->dasar_dokumen_kerjasama = $id;

        $this->fakultas = Fakultas::whereNot('id', 1000)->get();
        $this->getBentukKegiatan = LapkermaRefBentukKegiatan::get();
        $this->getIndikatorKinerja = LapkermaRefIndikatorKinerja::get();
        $this->getSasaranKegiatan = LapkermaRefSasaranKegiatan::get();
        $this->sumberDana = ReferensiSumberDanaLapkerma::get();
        $this->searchBadanKemitraan = ReferensiBadanKemitraan::get();
        $this->badanKemitraanOptions = ReferensiBadanKemitraan::whereNotIn('id', [10, 11])->get();
        $this->jenisKerjasama = JenisKerjasama::get();
        $this->regionKerjasama = Region::get();
        $this->negaraKerjasama = Negara::get();
        $this->kegiatanKerjasama = KegiatanKerjasama::get();
        $this->statusKerjasama = StatusKerjasama::get();
        $this->jenisDokKerjasama = JenisDokumenKerjasama::get();
        $this->getSdgs = Sdgs::get();
        $this->jenisKerjasamaField = 1;
        $this->updatedJenisKerjasamaField();

        if (auth()->user()->role_id == 1 || auth()->user()->role_id == 99) {
            // if ($this->renderSwitch == 'moa') {
            $this->dasarDokKerjasama = DataMou::where('level', 1)->get();
            // } else {
            $this->dasarDokKerjasama2 = DataMoa::get();
            // }
            $this->prodiAll = Prodi::get();
        } else {
            // dd('test');
            $this->prodiAll = Prodi::where('id_fakultas', auth()->user()->fakultas_id)->get();
            $this->dasarDokKerjasama = DataMou::where('level', 1)->whereIn('fakultas_pihak', [auth()->user()->fakultas_id, 1000])->orderBy('id', 'desc')->get();
            $this->dasarDokKerjasama2 = DataMoa::where('fakultas_pihak', auth()->user()->fakultas_id)->orderBy('id', 'desc')->get();
        }
    }

    public function render()
    {
        // dd(auth()->user());
        // if ($this->renderSwitch == 'moa') {
        $this->getProdiMitras = ProdiMitra::get();
        $this->getFakultasMitras = FakultasPihak::get();

        return view('livewire.input.moa');
        // } else {
        //     return view('livewire.input.ia');
        // }

    }

    public function redirek()
    {
        return redirect()->route('moa-in');
    }

    public function pushNamaInstansi($key, $id, $nama, $status)
    {
        $this->nama_pihak[$key] = $nama;
        $this->arrayMitra[$key] = $id;
        $this->lockInstansi[$key] = 1;
        $this->status[$key] = $status;

        if (auth()->user()->role_id != 1) {
            if ($id == 1) {
                $this->fakultas_pihak[$key] = auth()->user()->fakultas_id;
                if (auth()->user()->role_id != 4) {
                    $this->prodiPihak[$key] = [auth()->user()->prodi_id];
                    $this->arrayNamaProdi[$key] = [auth()->user()->prodi->nama_resmi];
                }
            }
        }
    }

    public function pushNamaFakultas($key, $id, $nama)
    {
        $this->nama_fakultas[$key] = $nama;
        $this->fakultas_pihak[$key] = $id;
        // $this->arrayFakultas[$key] = $id;
        $this->lockFakultas[$key] = 1;
    }

    public function clearLockInstansi($key)
    {
        $this->fakultas_pihak[$key] = null;

        // dd($this->fakultas_pihak[$key]);
        $this->nama_pihak[$key] = null;
        $this->arrayMitra[$key] = null;
        $this->lockInstansi[$key] = null;
        $this->status[$key] = null;
        $this->prodiPihak[$key] = null;
    }

    public function clearLockFakultas($key)
    {
        $this->nama_fakultas[$key] = null;
        $this->fakultas_pihak[$key] = null;
        $this->lockFakultas[$key] = null;
        $this->prodiPihak[$key] = null;
    }

    public function updatedSdgs()
    {
        if ($this->sdgs != 0) {
            array_push($this->arraySdgs, $this->sdgs);
        }
        $this->reset('sdgs');
    }

    public function minArraySdgs($i)
    {
        unset($this->arraySdgs[$i]);
    }

    public function pushProdiMitra($key, $id, $nama)
    {
        if (isset($this->prodiPihak[$key])) {
            $results = array_search($id, $this->prodiPihak[$key], true);
            if ($results !== false) {
                unset($this->prodiPihak[$key][$results]);
            } else {
                array_push($this->prodiPihak[$key], $id);
                array_push($this->arrayNamaProdi[$key], $nama);
            }
            $this->reset('searchProdiMitra');
        } else {
            $this->prodiPihak[$key] = [];
            $this->arrayNamaProdi[$key] = [];
            array_push($this->arrayNamaProdi[$key], $nama);
            array_push($this->prodiPihak[$key], $id);
            $this->reset('searchProdiMitra');
        }
    }

    public function unsetFakultas($key, $id)
    {
        $results = array_search($id, $this->prodiPihak[$key], true);
        if ($results !== false) {
            unset($this->prodiPihak[$key][$results]);
        }
    }

    public function updatedJenisKerjasamaField()
    {
        if ($this->jenisKerjasamaField == 1) {
            $this->region = 1;
            $this->negara = 'Indonesia';
        } else {
            $this->reset('region', 'negara');
            $this->dispatchBrowserEvent('contentChanged');
        }
    }

    public function updatedNegara()
    {
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function updatedBentukKegiatan()
    {
        if ($this->bentukKegiatan != 0) {
            array_push($this->arrayBentukKegiatan, $this->bentukKegiatan);
        }
        $this->reset('bentukKegiatan');
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function minArrayBentuk($i)
    {
        unset($this->arrayBentukKegiatan[$i]);
    }

    public function takeArray($add)
    {
        array_push($this->inputs, $add);
        // dd($this->inputs);
        // if ($this->arrayJawaban < 8) {
        //     $this->arrayJawaban++;
        // }
    }

    public function takeSuccess()
    {
        $this->showLoadFiles = true;
    }

    public function takeError()
    {
        $this->reset('files');
    }

    public function updatedFiles()
    {
        $this->validate([
            'files.*' => 'mimetypes:application/pdf|max:1024'
        ]);
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function minArrayPihak($key)
    {
        unset($this->inputs[$key]);
        unset($this->status[$key]);
        unset($this->nama_pihak[$key]);
        unset($this->fakultas_pihak[$key]);
        unset($this->alamat_pihak[$key]);
        unset($this->nama_pejabat_pihak[$key]);
        unset($this->jabatan_pejabat_pihak[$key]);
        unset($this->pj_pihak[$key]);
        unset($this->jabatan_pj_pihak[$key]);
        unset($this->email_pj_pihak[$key]);
        unset($this->hp_pj_pihak[$key]);
        unset($this->prodiPihak[$key]);
    }

    public function error1()
    {
        $this->files = null;
        $this->resetErrorBag();
    }

    public function updatedStatus()
    {
        foreach (range(0, $this->arrayJawaban) as $key => $value) {
            if (isset($this->status[$key])) {
                if ($this->status[$key] == 3) {
                    //   $this->dispatchBrowserEvent('disableSelect'.$value);
                    $this->fakultas_pihak[$value] = null;
                    $this->prodiPihak[$value] = null;
                } else {
                    // $this->fakultas_pihak[$value] = auth()->user()->fakultas_id;
                    // $this->dispatchBrowserEvent('enableSelect'.$value);
                }
            }
        }
    }

    public function validasiSave()
    {

        $this->validate([
            'tempat_pelaksanaan' => 'required',
            'jenis_dokumen_kerjasama' => 'required',
            'nomor_unhas' => 'required|min:5',
            'judul_kerjasama' => 'required',
            'deskripsi' => 'required',
            'tanggal_ttd' => 'date|required',
            'tanggal_awal' => 'date|required',
            'tanggal_berakhir' => 'date|required',
            'status_kerjasama' => 'required',
            'jangka_waktu' => 'required',
        ]);

        if ($this->jenis_dokumen_kerjasama == 2) {
            $this->validate([
                'dasar_dokumen_kerjasama' => 'required'
            ]);
        }
        if ($this->jenisKerjasamaField == 2) {
            $this->validate([
                'region' => 'required',
                'negara' => 'required',
                'files' => 'required',
            ]);
        } else {
            $this->validate([
                'tingkat' => 'required',
                'nomor_mitra' => 'required',
            ]);
        }
        foreach ($this->inputs as $key => $value) {
            $this->validate([
                "status.$value" => 'required',
            ]);

            $this->validate([
                "nama_pihak.$value" => 'required',
                "alamat_pihak.$value" => 'required',
                "negara_pihak.$value" => 'required',
                "koordinat_pihak.$value" => 'required',
                "nama_pejabat_pihak.$value" => 'required',
                "jabatan_pejabat_pihak.$value" => 'required',
                "pj_pihak.$value" => 'required',
                "jabatan_pj_pihak.$value" => 'required',
                "email_pj_pihak.$value" => 'required',
                "hp_pj_pihak.$value" => 'required',
            ]);

            if ($this->status[$value] == 3) {
                $this->validate([
                    'badanKemitraan.' . $value => 'required',
                ]);
                if ($this->badanKemitraan[$value] == 99) {
                    $this->validate([
                        'lainnya.' . $value => 'required',
                    ]);
                }
            } elseif ($this->status[$value] == 2) {
                $this->validate([
                    'fakultas_pihak.' . $value => 'required',
                    'prodiPihak.' . $value => 'required',
                ]);
            } else {
                $this->validate([
                    'ptqs.' . $value => 'required',
                    'fakultas_pihak.' . $value => 'required',
                    'prodiPihak.' . $value => 'required',
                ]);
            }
        }


        $this->validate([
            'arrayBentukKegiatan' => 'required'
        ]);

        // validate bentuk kegiatan
        foreach ($this->arrayBentukKegiatan as $key => $value) {
            $this->validate([
                'arrayKinerja.' . $key => 'required',
                'arraySasaran.' . $key => 'required',
                // 'arraySdgs.'.$key => 'required',
            ]);
        }

        $this->validate([
            // 'sdgs' => 'required',
            'arraySdgs.' . $key => 'required',
        ]);
    }

    public function saveMoA()
    {
        $this->validasiSave();
        $hitung = 0;
        foreach ($this->inputs as $key => $value) {
            $namanama = Str::lower($this->nama_pihak[$key]);
            $arrayNamaPenggiat[$key] = $this->nama_pihak[$key];
            if ($namanama == 'universitas hasanuddin') {
                $alamatPihak1 = $this->alamat_pihak[$key];
                $namaPihak1 = $this->nama_pihak[$key];
                $namaPejabat1 = $this->nama_pejabat_pihak[$key];
                $jabatanPejabat1 = $this->jabatan_pejabat_pihak[$key] ?? null;
                $pj1 = $this->pj_pihak[$key] ?? null;
                $jabatanPj1 = $this->jabatan_pj_pihak[$key] ?? null;
                $emailPj1 = $this->email_pj_pihak[$key] ?? null;
                $fakultas_pihak = $this->fakultas_pihak[$key];
                $hpPj1 = $this->hp_pj_pihak[$key] ?? null;
                $hitung++;
            }
        }
        // membuat kode sistem dokumen
        $uuid = DataMoa::max('id');
        $uuid = str_pad($uuid + 1, 3, '0', STR_PAD_LEFT);
        $uuid = 'MoA-' . date('y') . $uuid;

        if ($hitung == 0) {
            $this->emit('alerts', ['pesan' => 'Gagal ditambahkan, Unhas tidak disertakan dalam penggiat kerjasama', 'icon' => 'error']);
        } else {
            if ($this->files) {
                DB::beginTransaction();
                try {
                    $store = DataMoa::firstOrCreate([
                        'nomor_dok_unhas' => $this->nomor_unhas,
                        'prodi_id' => auth()->user()->prodi_id,
                    ], [
                        'tanggal_ttd' => $this->tanggal_ttd,
                        'jenis_kerjasama' => $this->jenisKerjasamaField,
                        'tingkat' => $this->jenisKerjasamaField == 2 ? '4' : $this->tingkat,
                        'negara' => $this->negara,
                        'region' => $this->region,
                        'uuid' => $uuid,
                        'tempat_pelaksanaan' => $this->tempat_pelaksanaan,
                        'status' => $this->status_kerjasama,
                        'tanggal_awal' => $this->tanggal_awal,
                        'tanggal_berakhir' => $this->tanggal_berakhir,
                        'jangka_waktu' => $this->jangka_waktu,
                        'level' => 1,
                        'nomor_dok_mitra' => $this->nomor_mitra,
                        'judul' => $this->judul_kerjasama,
                        'fakultas_pihak' => $fakultas_pihak,
                        'anggaran' => $this->anggaran,
                        'sumber_dana' => $this->sumber_dana,
                        'dasar_dokumen' => $this->dasar_dokumen_kerjasama,
                        'nama_prodi' => auth()->user()->prodi->nama_resmi,
                        'deskripsi' => $this->deskripsi,
                        'nama_pihak' => $namaPihak1,
                        'alamat_pihak' => $alamatPihak1,
                        'nama_pejabat_pihak' => $namaPejabat1,
                        'jabatan_pejabat_pihak' => $jabatanPejabat1,
                        'pj_pihak' => $pj1,
                        'jabatan_pj_pihak' => $jabatanPj1,
                        'email_pj_pihak' => $emailPj1,
                        'hp_pj_pihak' => $hpPj1,
                        'penggiat' => json_encode($arrayNamaPenggiat),
                        'sdgs' => json_encode($this->arraySdgs),
                        'uploaded_by' => auth()->user()->name,
                    ]);
                    if ($store->wasRecentlyCreated) {
                        $code = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        foreach ($this->files as $file) {
                            $random = substr(str_shuffle($code), 0, 3);
                            $namaDokumen = 'MoA' . $uuid . $random . '.' . $file->extension();
                            $file->storeAs('public/DokumenMoA', $namaDokumen);
                            $store->dokumenMoA()->firstOrCreate([
                                'url' => $namaDokumen,
                                'kerjasama_id' => $store->id
                            ]);
                        }


                        foreach ($this->inputs as $key => $value) {
                            $storePenggiatKerjasama = DataMoaPenggiat::create([
                                'id_lapkerma' => $store->id,
                                'pihak' => $value + 1,
                                'status_pihak' => $this->status[$key],
                                'nama_pihak' => $this->nama_pihak[$key],
                                'fakultas_pihak' => $this->fakultas_pihak[$key] ?? '',
                                'alamat_pihak' => $this->alamat_pihak[$key],
                                'nama_pejabat_pihak' => $this->nama_pejabat_pihak[$key],
                                'jabatan_pejabat_pihak' => $this->jabatan_pejabat_pihak[$key] ?? '',
                                'pj_pihak' => $this->pj_pihak[$key] ?? null,
                                'jabatan_pj_pihak' => $this->jabatan_pj_pihak[$key] ?? null,
                                'email_pj_pihak' => $this->email_pj_pihak[$key] ?? null,
                                'hp_pj_pihak' => $this->hp_pj_pihak[$key] ?? null,
                                'ptqs' => $this->ptqs[$key] ?? null,
                                'badan_kemitraan' => $this->badanKemitraan[$key] ?? '',
                                'uploaded_by' => auth()->user()->name,
                                'prodi' => json_encode(optional($this->prodiPihak)[$key]) ?? null
                            ]);
                            if (optional($this->badanKemitraan)[$key] == 99) {
                                $storePenggiatKerjasama->update([
                                    'badan_kemitraan' => $this->lainnya[$key]
                                ]);
                            }
                            $storePJ = PenanggungJawab::updateOrCreate(
                                [
                                    'name' => $this->pj_pihak[$value] ?? null,
                                ],
                                [
                                    'designation' => $this->jabatan_pj_pihak[$value] ?? null,
                                    'email' => $this->email_pj_pihak[$value] ?? null,
                                    'phone_number' => $this->hp_pj_pihak[$value] ?? null
                                ]
                            );

                            $storePejabat = Pejabat::updateOrCreate(
                                [
                                    'nama' => $this->nama_pejabat_pihak[$value] ?? null,
                                ],
                                [
                                    'jabatan' => $this->jabatan_pejabat_pihak[$value] ?? null
                                ]

                            );

                            $storeInstansi = Instansi::updateOrCreate(
                                [
                                    'name' => $this->nama_pihak[$value] ?? null,
                                ],
                                [

                                    'address' => $this->alamat_pihak[$value],
                                    'negara_id' => $this->negara_pihak[$value],
                                    'coordinates' => $this->koordinat_pihak[$value] ?? '',
                                    'ptqs' => $this->ptqs[$value] ?? null,
                                    'status' => $this->status[$value] ?? null,
                                    'badan_kemitraan' => isset($this->ptqs[$key]) && $this->negara_pihak[$value] == 103 && ($this->ptqs[$key] == 1 || $this->ptqs[$key] == 2)
                                    ? 7
                                    : ($this->negara_pihak[$value] != 103
                                        ? 8
                                        : ($this->badanKemitraan[$value] ?? null)),
                                ]
                            );
                            if (optional($this->badanKemitraan)[$value] == 99) {
                                $storeInstansi->update([
                                    'badan_kemitraan' => $this->lainnya[$value] ?? null
                                ]);
                            }

                            $storePenggiatKerjasama2 = MoaPenggiat::create(
                                [
                                    'id_lapkerma' => $store->id,
                                    'id_pihak' => $storeInstansi->id,
                                    'pihak' => $this->nama_pihak[$value],
                                    'id_pj' => $storePJ->id,
                                    'id_pejabat' => $storePejabat->id,
                                    'fakultas_pihak' => $this->fakultas_pihak[$key] ?? null,
                                    'prodi' => json_encode(optional($this->prodiPihak)[$key]) ?? null,
                                ]
                            );
                        }


                        foreach ($this->arrayBentukKegiatan as $key => $value) {
                            $storeBentukKegiatanKerjasama = DataMoaBentukKegiatanKerjasama::create([
                                'id_moa' => $store->id,
                                'nilai_kontrak' => $this->nilai_kontrak[$key] ?? null,
                                'volume_satuan' => $this->volume_satuan[$key] ?? null,
                                'volume_luaran' => $this->volume_luaran[$key] ?? null,
                                'keterangan' => $this->keterangan[$key] ?? null,
                                'id_ref_bentuk_kegiatan' => $value,
                                'id_ref_indikator_kinerja' => $this->arrayKinerja[$key] ?? null,
                                'id_ref_sasaran_kegiatan' => $this->arraySasaran[$key] ?? null,
                                // 'id_sdgs' => $this->arraySdgs[$key]??null,
                                // 'id_sdgs' => $this->sdgs??null,
                            ]);
                        }
                        DB::commit();
                        $this->emit('alerts', ['pesan' => 'Data Berhasil Ditambahkan', 'icon' => 'success']);
                        dd($store);
                    } else {
                        $this->emit('alerts', ['pesan' => 'Invalid Proses, Data Duplikat', 'icon' => 'error']);
                    }
                } catch (ERROR $th) {
                    DB::rollback();
                    dd($th);
                    $this->emit('alerts', ['pesan' => 'Invalid Proses, Gagal Ditambahkan', 'icon' => 'error']);
                }
            } else {
                $this->emit('alerts', ['pesan' => 'Tidak Ada File Pendukung, Data Gagal Ditambahkan', 'icon' => 'error']);
            }
        }
    }

    public function saveMoAPimpinan()
    {
        $this->validasiSave();
        $hitung = 0;
        foreach ($this->inputs as $key => $value) {
            $namanama = Str::lower($this->nama_pihak[$key]);
            $arrayNamaPenggiat[$key] = $this->nama_pihak[$key];
            if ($namanama == 'universitas hasanuddin') {
                $arrayNamaProdiUH = $this->arrayNamaProdi[$key];
                $alamatPihak1 = $this->alamat_pihak[$key];
                $namaPihak1 = $this->nama_pihak[$key];
                $namaPejabat1 = $this->nama_pejabat_pihak[$key];
                $jabatanPejabat1 = $this->jabatan_pejabat_pihak[$key] ?? null;
                $pj1 = $this->pj_pihak[$key] ?? null;
                $jabatanPj1 = $this->jabatan_pj_pihak[$key] ?? null;
                $emailPj1 = $this->email_pj_pihak[$key] ?? null;
                $fakultas_pihak = $this->fakultas_pihak[$key];
                $hpPj1 = $this->hp_pj_pihak[$key] ?? null;
                $prodi_pihak = $this->prodiPihak[$key] ?? null;
                $hitung++;
            }
        }
        // membuat kode sistem dokumen
        $uuid = DataMoa::max('id');
        $uuid = str_pad($uuid + 1, 3, '0', STR_PAD_LEFT);
        $uuid = 'MoA-' . date('y') . $uuid;

        if ($hitung == 0) {
            $this->emit('alerts', ['pesan' => 'Gagal ditambahkan, Unhas tidak disertakan dalam penggiat kerjasama', 'icon' => 'error']);
        } else {
            if ($this->files) {
                DB::beginTransaction();
                try {
                    foreach ($prodi_pihak as $key => $value) {
                        $store = DataMoa::firstOrCreate([
                            'nomor_dok_unhas' => $this->nomor_unhas,
                            'prodi_id' => $value,
                        ], [
                            'tanggal_ttd' => $this->tanggal_ttd,
                            'jenis_kerjasama' => $this->jenisKerjasamaField,
                            'tingkat' => $this->jenisKerjasamaField == 2 ? '4' : $this->tingkat,
                            'negara' => $this->negara,
                            'region' => $this->region,
                            'uuid' => $uuid,
                            'tempat_pelaksanaan' => $this->tempat_pelaksanaan,
                            'status' => $this->status_kerjasama,
                            'tanggal_awal' => $this->tanggal_awal,
                            'tanggal_berakhir' => $this->tanggal_berakhir,
                            'jangka_waktu' => $this->jangka_waktu,
                            'level' => 1,
                            'nomor_dok_mitra' => $this->nomor_mitra,
                            'judul' => $this->judul_kerjasama,
                            'fakultas_pihak' => $fakultas_pihak,
                            'anggaran' => $this->anggaran,
                            'dasar_dokumen' => $this->dasar_dokumen_kerjasama,
                            'sumber_dana' => $this->sumber_dana,
                            'nama_prodi' => $arrayNamaProdiUH[$key],
                            'deskripsi' => $this->deskripsi,
                            'nama_pihak' => $namaPihak1,
                            'alamat_pihak' => $alamatPihak1,
                            'nama_pejabat_pihak' => $namaPejabat1,
                            'jabatan_pejabat_pihak' => $jabatanPejabat1,
                            'pj_pihak' => $pj1,
                            'jabatan_pj_pihak' => $jabatanPj1,
                            'email_pj_pihak' => $emailPj1,
                            'hp_pj_pihak' => $hpPj1,
                            'penggiat' => json_encode($arrayNamaPenggiat),
                            'sdgs' => json_encode($this->arraySdgs),
                            'uploaded_by' => auth()->user()->name,
                        ]);

                        if ($store->wasRecentlyCreated) {
                            $code = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            foreach ($this->files as $file) {
                                $random = substr(str_shuffle($code), 0, 3);
                                $namaDokumen = 'MoA' . $uuid . $random . '.' . $file->extension();
                                $file->storeAs('public/DokumenMoA', $namaDokumen);
                                $store->dokumenMoA()->firstOrCreate([
                                    'url' => $namaDokumen,
                                    'kerjasama_id' => $store->id
                                ]);
                                // dd($namaDokumen);
                            }
                            foreach ($this->inputs as $key => $value) {
                                $storePenggiatKerjasama = DataMoaPenggiat::create([
                                    'id_lapkerma' => $store->id,
                                    'pihak' => $value + 1,
                                    'status_pihak' => $this->status[$key],
                                    'nama_pihak' => $this->nama_pihak[$key],
                                    'fakultas_pihak' => $this->fakultas_pihak[$key] ?? '',
                                    'alamat_pihak' => $this->alamat_pihak[$key],
                                    'nama_pejabat_pihak' => $this->nama_pejabat_pihak[$key],
                                    'jabatan_pejabat_pihak' => $this->jabatan_pejabat_pihak[$key] ?? '',
                                    'pj_pihak' => $this->pj_pihak[$key] ?? null,
                                    'jabatan_pj_pihak' => $this->jabatan_pj_pihak[$key] ?? '',
                                    'email_pj_pihak' => $this->email_pj_pihak[$key] ?? null,
                                    'hp_pj_pihak' => $this->hp_pj_pihak[$key] ?? null,
                                    'ptqs' => $this->ptqs[$key] ?? null,
                                    'badan_kemitraan' => $this->badanKemitraan[$key] ?? '',
                                    'uploaded_by' => auth()->user()->name,
                                    'prodi' => json_encode($this->prodiPihak[$key] ?? null)
                                ]);
                                if (optional($this->badanKemitraan)[$key] == 99) {
                                    $storePenggiatKerjasama->update([
                                        'badan_kemitraan' => $this->lainnya[$key]
                                    ]);
                                }
                                $storePJ = PenanggungJawab::updateOrCreate(
                                    [
                                        'name' => $this->pj_pihak[$value] ?? null,
                                    ],
                                    [
                                        'designation' => $this->jabatan_pj_pihak[$value] ?? null,
                                        'email' => $this->email_pj_pihak[$value] ?? null,
                                        'phone_number' => $this->hp_pj_pihak[$value] ?? null
                                    ]
                                );

                                $storePejabat = Pejabat::updateOrCreate(
                                    [
                                        'nama' => $this->nama_pejabat_pihak[$value] ?? null,
                                    ],
                                    [
                                        'jabatan' => $this->jabatan_pejabat_pihak[$value] ?? null
                                    ]

                                );

                                $storeInstansi = Instansi::updateOrCreate(
                                    [
                                        'name' => $this->nama_pihak[$value] ?? null,
                                    ],
                                    [

                                        'address' => $this->alamat_pihak[$value],
                                        'negara_id' => $this->negara_pihak[$value],
                                        'coordinates' => $this->koordinat_pihak[$value] ?? '',
                                        'ptqs' => $this->ptqs[$value] ?? null,
                                        'status' => $this->status[$value] ?? null,
                                        'badan_kemitraan' =>  isset($this->ptqs[$key]) && $this->negara_pihak[$value] == 103 && ($this->ptqs[$key] == 1 || $this->ptqs[$key] == 2)
                                        ? 7
                                        : ($this->negara_pihak[$value] != 103
                                            ? 8
                                            : ($this->badanKemitraan[$value] ?? null)),
                                    ]
                                );
                                if (optional($this->badanKemitraan)[$value] == 99) {
                                    $storeInstansi->update([
                                        'badan_kemitraan' => $this->lainnya[$value] ?? null
                                    ]);
                                }

                                $storePenggiatKerjasama2 = MoaPenggiat::create(
                                    [
                                        'id_lapkerma' => $store->id,
                                        'id_pihak' => $storeInstansi->id,
                                        'pihak' => $this->nama_pihak[$value],
                                        'id_pj' => $storePJ->id,
                                        'id_pejabat' => $storePejabat->id,
                                        'fakultas_pihak' => $this->fakultas_pihak[$value] ?? null,
                                        'prodi' => json_encode(optional($this->prodiPihak)[$key]) ?? null,
                                    ]
                                );
                            }


                            foreach ($this->arrayBentukKegiatan as $key => $value) {
                                $storeBentukKegiatanKerjasama = DataMoaBentukKegiatanKerjasama::create([
                                    'id_moa' => $store->id,
                                    'nilai_kontrak' => $this->nilai_kontrak[$key] ?? null,
                                    'volume_satuan' => $this->volume_satuan[$key] ?? null,
                                    'volume_luaran' => $this->volume_luaran[$key] ?? null,
                                    'keterangan' => $this->keterangan[$key] ?? null,
                                    'id_ref_bentuk_kegiatan' => $value,
                                    'id_ref_indikator_kinerja' => $this->arrayKinerja[$key] ?? null,
                                    'id_ref_sasaran_kegiatan' => $this->arraySasaran[$key] ?? null,
                                    // 'id_sdgs' => $this->arraySdgs[$key]??null,
                                    // 'id_sdgs' => $this->sdgs??null,
                                ]);
                            }
                        }
                    }
                    DB::commit();
                    $this->emit('alerts2', ['pesan' => 'Data Berhasil Ditambahkan', 'icon' => 'success']);
                } catch (ERROR $th) {
                    DB::rollback();
                    dd($th);
                    $this->emit('alerts', ['pesan' => 'Invalid Proses, Gagal Ditambahkan', 'icon' => 'error']);
                }
            } else {
                $this->emit('alerts', ['pesan' => 'Tidak Ada File Pendukung, Data Gagal Ditambahkan', 'icon' => 'error']);
            }
        }
    }

    public function saveIa()
    {
        $this->validasiSave();
        $hitung = 0;
        foreach ($this->inputs as $key => $value) {
            $namanama = Str::lower($this->nama_pihak[$key]);
            $arrayNamaPenggiat[$key] = $this->nama_pihak[$key];
            if ($namanama == 'universitas hasanuddin') {
                $alamatPihak1 = $this->alamat_pihak[$key];
                $namaPihak1 = $this->nama_pihak[$key];
                $namaPejabat1 = $this->nama_pejabat_pihak[$key];
                $jabatanPejabat1 = $this->jabatan_pejabat_pihak[$key] ?? null;
                $pj1 = $this->pj_pihak[$key] ?? null;
                $jabatanPj1 = $this->jabatan_pj_pihak[$key] ?? null;
                $emailPj1 = $this->email_pj_pihak[$key] ?? null;
                $fakultas_pihak = $this->fakultas_pihak[$key];
                $hpPj1 = $this->hp_pj_pihak[$key] ?? null;
                $hitung++;
            }
        }
        // membuat kode sistem dokumen
        $uuid = DataIa::max('id');
        $uuid = str_pad($uuid + 1, 3, '0', STR_PAD_LEFT);
        $uuid = 'IA-' . date('y') . $uuid;

        if ($hitung == 0) {
            $this->emit('alerts', ['pesan' => 'Gagal ditambahkan, Unhas tidak disertakan dalam penggiat kerjasama', 'icon' => 'error']);
        } else {
            if ($this->files) {
                DB::beginTransaction();
                try {
                    $store = DataIa::firstOrCreate([
                        'nomor_dok_unhas' => $this->nomor_unhas,
                        'prodi_id' => auth()->user()->prodi_id,
                    ], [
                        'tanggal_ttd' => $this->tanggal_ttd,
                        'jenis_kerjasama' => $this->jenisKerjasamaField,
                        'tingkat' => $this->jenisKerjasamaField == 2 ? '4' : $this->tingkat,
                        'negara' => $this->negara,
                        'region' => $this->region,
                        'uuid' => $uuid,
                        'tempat_pelaksanaan' => $this->tempat_pelaksanaan,
                        'status' => $this->status_kerjasama,
                        'tanggal_awal' => $this->tanggal_awal,
                        'tanggal_berakhir' => $this->tanggal_berakhir,
                        'jangka_waktu' => $this->jangka_waktu,
                        'level' => 1,
                        'nomor_dok_mitra' => $this->nomor_mitra,
                        'judul' => $this->judul_kerjasama,
                        'fakultas_pihak' => $fakultas_pihak,
                        'anggaran' => $this->anggaran,
                        'dasar_dokumen' => $this->dasar_dokumen_kerjasama,
                        'sumber_dana' => $this->sumber_dana,
                        'nama_prodi' => auth()->user()->prodi->nama_resmi,
                        'deskripsi' => $this->deskripsi,
                        'nama_pihak' => $namaPihak1,
                        'alamat_pihak' => $alamatPihak1,
                        'nama_pejabat_pihak' => $namaPejabat1,
                        'jabatan_pejabat_pihak' => $jabatanPejabat1,
                        'pj_pihak' => $pj1,
                        'jabatan_pj_pihak' => $jabatanPj1,
                        'email_pj_pihak' => $emailPj1,
                        'hp_pj_pihak' => $hpPj1,
                        'penggiat' => json_encode($arrayNamaPenggiat),
                        'sdgs' => json_encode($this->arraySdgs),
                        'uploaded_by' => auth()->user()->name,
                    ]);

                    if ($store->wasRecentlyCreated) {
                        $code = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        foreach ($this->files as $file) {
                            $random = substr(str_shuffle($code), 0, 3);
                            $namaDokumen = 'IA' . $uuid . $random . '.' . $file->extension();
                            $file->storeAs('public/DokumenIA', $namaDokumen);
                            $store->dokumenIA()->firstOrCreate([
                                'url' => $namaDokumen,
                                'kerjasama_id' => $store->id
                            ]);
                        }
                        foreach ($this->inputs as $key => $value) {
                            $storePenggiatKerjasama = DataIaPenggiat::create([
                                'id_lapkerma' => $store->id,
                                'pihak' => $value + 1,
                                'status_pihak' => $this->status[$key],
                                'nama_pihak' => $this->nama_pihak[$key],
                                'fakultas_pihak' => $this->fakultas_pihak[$key] ?? '',
                                'alamat_pihak' => $this->alamat_pihak[$key],
                                'nama_pejabat_pihak' => $this->nama_pejabat_pihak[$key],
                                'jabatan_pejabat_pihak' => $this->jabatan_pejabat_pihak[$key] ?? '',
                                'pj_pihak' => $this->pj_pihak[$key] ?? null,
                                'jabatan_pj_pihak' => $this->jabatan_pj_pihak[$key] ?? '',
                                'email_pj_pihak' => $this->email_pj_pihak[$key] ?? null,
                                'hp_pj_pihak' => $this->hp_pj_pihak[$key] ?? null,
                                'ptqs' => $this->ptqs[$key] ?? null,
                                'badan_kemitraan' => $this->badanKemitraan[$key] ?? '',
                                'uploaded_by' => auth()->user()->name,
                                'prodi' => json_encode($this->prodiPihak[$key] ?? null)
                            ]);
                            if (optional($this->badanKemitraan)[$key] == 99) {
                                $storePenggiatKerjasama->update([
                                    'badan_kemitraan' => $this->lainnya[$key]
                                ]);
                            }

                            $storePJ = PenanggungJawab::updateOrCreate(
                                [
                                    'name' => $this->pj_pihak[$value] ?? null,
                                ],
                                [
                                    'designation' => $this->jabatan_pj_pihak[$value] ?? null,
                                    'email' => $this->email_pj_pihak[$value] ?? null,
                                    'phone_number' => $this->hp_pj_pihak[$value] ?? null
                                ]
                            );

                            $storePejabat = Pejabat::updateOrCreate(
                                [
                                    'nama' => $this->nama_pejabat_pihak[$value] ?? null,
                                ],
                                [
                                    'jabatan' => $this->jabatan_pejabat_pihak[$value] ?? null
                                ]

                            );

                            $storeInstansi = Instansi::updateOrCreate(
                                [
                                    'name' => $this->nama_pihak[$value] ?? null,
                                ],
                                [

                                    'address' => $this->alamat_pihak[$value],
                                    'negara_id' => $this->negara_pihak[$value],
                                    'coordinates' => $this->koordinat_pihak[$value] ?? '',
                                    'ptqs' => $this->ptqs[$value] ?? null,
                                    'status' => $this->status[$value] ?? null,
                                    'badan_kemitraan' =>  isset($this->ptqs[$key]) && $this->negara_pihak[$value] == 103 && ($this->ptqs[$key] == 1 || $this->ptqs[$key] == 2)
                                    ? 7
                                    : ($this->negara_pihak[$value] != 103
                                        ? 8
                                        : ($this->badanKemitraan[$value] ?? null)),
                                ]
                            );
                            if (optional($this->badanKemitraan)[$value] == 99) {
                                $storeInstansi->update([
                                    'badan_kemitraan' => $this->lainnya[$value] ?? null
                                ]);
                            }

                            $storePenggiatKerjasama2 = IaPenggiat::create(
                                [
                                    'id_lapkerma' => $store->id,
                                    'id_pihak' => $storeInstansi->id,
                                    'pihak' => $this->nama_pihak[$value],
                                    'id_pj' => $storePJ->id,
                                    'id_pejabat' => $storePejabat->id,
                                    'fakultas_pihak' => $this->fakultas_pihak[$value] ?? null,
                                    'prodi' => json_encode(optional($this->prodiPihak)[$key]) ?? null,
                                ]
                            );
                        }
                        foreach ($this->arrayBentukKegiatan as $key => $value) {
                            $storeBentukKegiatanKerjasama = DataIaBentukKegiatanKerjasama::create([
                                'id_ia' => $store->id,
                                'nilai_kontrak' => $this->nilai_kontrak[$key] ?? null,
                                'volume_satuan' => $this->volume_satuan[$key] ?? null,
                                'volume_luaran' => $this->volume_luaran[$key] ?? null,
                                'keterangan' => $this->keterangan[$key] ?? null,
                                'id_ref_bentuk_kegiatan' => $value,
                                'id_ref_indikator_kinerja' => $this->arrayKinerja[$key] ?? null,
                                'id_ref_sasaran_kegiatan' => $this->arraySasaran[$key] ?? null,
                                // 'id_sdgs' => $this->sdgs??null,
                                // 'id_sdgs' => $this->arraySdgs[$key]??null,
                            ]);
                        }
                        DB::commit();
                        $this->emit('alerts2', ['pesan' => 'Data Berhasil Ditambahkan', 'icon' => 'success']);
                    } else {
                        $this->emit('alerts', ['pesan' => 'Invalid Proses, Data Duplikat', 'icon' => 'error']);
                    }
                } catch (ERROR $th) {
                    DB::rollback();
                    // dd($th);
                    $this->emit('alerts', ['pesan' => 'Invalid Proses, Gagal Ditambahkan', 'icon' => 'error']);
                }
            } else {
                $this->emit('alerts', ['pesan' => 'Tidak Ada File Pendukung, Data Gagal Ditambahkan', 'icon' => 'error']);
            }
        }
    }

    public function saveIaPimpinan()
    {
        $this->validasiSave();
        $hitung = 0;
        foreach ($this->inputs as $key => $value) {
            $namanama = Str::lower($this->nama_pihak[$key]);
            $arrayNamaPenggiat[$key] = $this->nama_pihak[$key];
            if ($namanama == 'universitas hasanuddin') {
                $arrayNamaProdiUH = $this->arrayNamaProdi[$key];
                $status = $this->status[$key];
                $alamatPihak1 = $this->alamat_pihak[$key];
                $namaPihak1 = $this->nama_pihak[$key];
                $namaPejabat1 = $this->nama_pejabat_pihak[$key];
                $jabatanPejabat1 = $this->jabatan_pejabat_pihak[$key] ?? null;
                $pj1 = $this->pj_pihak[$key] ?? null;
                $jabatanPj1 = $this->jabatan_pj_pihak[$key] ?? null;
                $emailPj1 = $this->email_pj_pihak[$key] ?? null;
                $fakultas_pihak = $this->fakultas_pihak[$key];
                $hpPj1 = $this->hp_pj_pihak[$key] ?? null;
                $prodi_pihak = $this->prodiPihak[$key] ?? null;
                $hitung++;
            }
        }

        // membuat kode sistem dokumen
        $uuid = DataIa::max('id');
        $uuid = str_pad($uuid + 1, 3, '0', STR_PAD_LEFT);
        $uuid = 'IA-' . date('y') . $uuid;

        if ($hitung == 0) {
            $this->emit('alerts', ['pesan' => 'Gagal ditambahkan, Unhas tidak disertakan dalam penggiat kerjasama', 'icon' => 'error']);
        } else {
            if ($this->files) {
                DB::beginTransaction();
                try {
                    foreach ($prodi_pihak as $key => $value) {
                        $store = DataIa::firstOrCreate([
                            'nomor_dok_unhas' => $this->nomor_unhas,
                            'prodi_id' => $value,
                        ], [
                            'tanggal_ttd' => $this->tanggal_ttd,
                            'jenis_kerjasama' => $this->jenisKerjasamaField,
                            'tingkat' => $this->jenisKerjasamaField == 2 ? '4' : $this->tingkat,
                            'negara' => $this->negara,
                            'region' => $this->region,
                            'uuid' => $uuid,
                            'tempat_pelaksanaan' => $this->tempat_pelaksanaan,
                            'status' => $this->status_kerjasama,
                            'tanggal_awal' => $this->tanggal_awal,
                            'tanggal_berakhir' => $this->tanggal_berakhir,
                            'jangka_waktu' => $this->jangka_waktu,
                            'level' => 1,
                            'nomor_dok_mitra' => $this->nomor_mitra,
                            'judul' => $this->judul_kerjasama,
                            'fakultas_pihak' => $fakultas_pihak,
                            'anggaran' => $this->anggaran,
                            'dasar_dokumen' => $this->dasar_dokumen_kerjasama,
                            'sumber_dana' => $this->sumber_dana,
                            'nama_prodi' => $arrayNamaProdiUH[$key],
                            'deskripsi' => $this->deskripsi,
                            'nama_pihak' => $namaPihak1,
                            'alamat_pihak' => $alamatPihak1,
                            'nama_pejabat_pihak' => $namaPejabat1,
                            'jabatan_pejabat_pihak' => $jabatanPejabat1,
                            'pj_pihak' => $pj1,
                            'jabatan_pj_pihak' => $jabatanPj1,
                            'email_pj_pihak' => $emailPj1,
                            'hp_pj_pihak' => $hpPj1,
                            'penggiat' => json_encode($arrayNamaPenggiat),
                            'sdgs' => json_encode($this->arraySdgs),
                            'uploaded_by' => auth()->user()->name,
                        ]);

                        if ($store->wasRecentlyCreated) {
                            $code = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            foreach ($this->files as $file) {
                                $random = substr(str_shuffle($code), 0, 3);
                                $namaDokumen = 'IA' . $uuid . $random . '.' . $file->extension();
                                $file->storeAs('public/DokumenIA', $namaDokumen);
                                $store->dokumenIA()->firstOrCreate([
                                    'url' => $namaDokumen,
                                    'kerjasama_id' => $store->id
                                ]);
                            }
                            foreach ($this->inputs as $key => $value) {
                                $storePenggiatKerjasama = DataIaPenggiat::create([
                                    'id_lapkerma' => $store->id,
                                    'pihak' => $value + 1,
                                    'status_pihak' => $this->status[$key],
                                    'nama_pihak' => $this->nama_pihak[$key],
                                    'fakultas_pihak' => $this->fakultas_pihak[$key] ?? '',
                                    'alamat_pihak' => $this->alamat_pihak[$key],
                                    'nama_pejabat_pihak' => $this->nama_pejabat_pihak[$key],
                                    'jabatan_pejabat_pihak' => $this->jabatan_pejabat_pihak[$key] ?? '',
                                    'pj_pihak' => $this->pj_pihak[$key] ?? null,
                                    'jabatan_pj_pihak' => $this->jabatan_pj_pihak[$key] ?? '',
                                    'email_pj_pihak' => $this->email_pj_pihak[$key] ?? null,
                                    'hp_pj_pihak' => $this->hp_pj_pihak[$key] ?? null,
                                    'ptqs' => $this->ptqs[$key] ?? null,
                                    'badan_kemitraan' => $this->badanKemitraan[$key] ?? '',
                                    'uploaded_by' => auth()->user()->name,
                                    'prodi' => json_encode($this->prodiPihak[$key] ?? null)
                                ]);
                                if (optional($this->badanKemitraan)[$key] == 99) {
                                    $storePenggiatKerjasama->update([
                                        'badan_kemitraan' => $this->lainnya[$key]
                                    ]);
                                }

                                $storePJ = PenanggungJawab::updateOrCreate(
                                    [
                                        'name' => $this->pj_pihak[$value] ?? null,
                                    ],
                                    [
                                        'designation' => $this->jabatan_pj_pihak[$value] ?? null,
                                        'email' => $this->email_pj_pihak[$value] ?? null,
                                        'phone_number' => $this->hp_pj_pihak[$value] ?? null
                                    ]
                                );

                                $storePejabat = Pejabat::updateOrCreate(
                                    [
                                        'nama' => $this->nama_pejabat_pihak[$value] ?? null,
                                    ],
                                    [
                                        'jabatan' => $this->jabatan_pejabat_pihak[$value] ?? null
                                    ]

                                );

                                $storeInstansi = Instansi::updateOrCreate(
                                    [
                                        'name' => $this->nama_pihak[$value] ?? null,
                                    ],
                                    [

                                        'address' => $this->alamat_pihak[$value],
                                        'negara_id' => $this->negara_pihak[$value],
                                        'coordinates' => $this->koordinat_pihak[$value] ?? '',
                                        'ptqs' => $this->ptqs[$value] ?? null,
                                        'status' => $this->status[$value] ?? null,
                                        'badan_kemitraan' =>  isset($this->ptqs[$key]) && $this->negara_pihak[$value] == 103 && ($this->ptqs[$key] == 1 || $this->ptqs[$key] == 2)
                                        ? 7
                                        : ($this->negara_pihak[$value] != 103
                                            ? 8
                                            : ($this->badanKemitraan[$value] ?? null)),
                                    ]
                                );
                                if (optional($this->badanKemitraan)[$value] == 99) {
                                    $storeInstansi->update([
                                        'badan_kemitraan' => $this->lainnya[$value] ?? null
                                    ]);
                                }

                                $storePenggiatKerjasama2 = IaPenggiat::create(
                                    [
                                        'id_lapkerma' => $store->id,
                                        'id_pihak' => $storeInstansi->id,
                                        'pihak' => $this->nama_pihak[$value],
                                        'id_pj' => $storePJ->id,
                                        'id_pejabat' => $storePejabat->id,
                                        'fakultas_pihak' => $this->fakultas_pihak[$value] ?? null,
                                        'prodi' => json_encode(optional($this->prodiPihak)[$key]) ?? null,
                                    ]
                                );
                            }
                            foreach ($this->arrayBentukKegiatan as $key => $value) {
                                $storeBentukKegiatanKerjasama = DataIaBentukKegiatanKerjasama::create([
                                    'id_ia' => $store->id,
                                    'nilai_kontrak' => $this->nilai_kontrak[$key] ?? null,
                                    'volume_satuan' => $this->volume_satuan[$key] ?? null,
                                    'volume_luaran' => $this->volume_luaran[$key] ?? null,
                                    'keterangan' => $this->keterangan[$key] ?? null,
                                    'id_ref_bentuk_kegiatan' => $value,
                                    'id_ref_indikator_kinerja' => $this->arrayKinerja[$key] ?? null,
                                    'id_ref_sasaran_kegiatan' => $this->arraySasaran[$key] ?? null,
                                    // 'id_sdgs' => $this->arraySdgs[$key]??null,
                                    'id_sdgs' => $this->sdgs ?? null,
                                ]);
                            }
                        }
                    }
                    DB::commit();
                    $this->emit('alerts2', ['pesan' => 'Data Berhasil Ditambahkan', 'icon' => 'success']);
                } catch (ERROR $th) {
                    DB::rollback();
                    dd($th);
                    $this->emit('alerts', ['pesan' => 'Invalid Proses, Gagal Ditambahkan', 'icon' => 'error']);
                }
            } else {
                $this->emit('alerts', ['pesan' => 'Tidak Ada File Pendukung, Data Gagal Ditambahkan', 'icon' => 'error']);
            }
        }
    }
}
