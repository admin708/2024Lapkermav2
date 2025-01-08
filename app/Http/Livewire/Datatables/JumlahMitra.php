<?php

namespace App\Http\Livewire\Datatables;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instansi;
use App\Models\Negara;
use App\Models\ReferensiBadanKemitraan;
use Illuminate\Support\Facades\DB;

class JumlahMitra extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $kerjasamaId, $orderBy, $orderDirection, $perPage, $tahun, $jenjang, $searchProdi;
    public $orderByText, $orderDirectionText, $kerjaSamaText, $tahunText, $jenjangText, $showModalsEdit;
    public $instansiTipe, $instansiTipeText, $availableYears = [];

    public $nama_pihak, $status, $alamat_pihak, $koordinat_pihak, $negara_pihak, $ptqs, $badanKemitraan, $lainnya;
    public $badanKemitraanOptions, $searchInstansiList, $negaraKerjasama;

    public function mount()
    {
        $this->orderBy = "name"; // Mengurutkan berdasarkan nama instansi
        $this->orderDirection = "asc";
        $this->orderByText = 'Urut Berdasarkan';
        $this->kerjaSamaText = 'Semua Kerja Sama';
        $this->tahunText = 'Semua Tahun';
        $this->instansiTipeText = 'Semua Instansi';
        $this->perPage = 10;
        $this->availableYears = $this->getAvailableYears(); // Dapatkan daftar tahun yang tersedia
        $this->badanKemitraanOptions = ReferensiBadanKemitraan::get();
        $this->negaraKerjasama = Negara::get();
        $this->showModalsEdit = false;
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function updatedNamaPihak($value)
    {
        if (!empty($this->nama_pihak)) {
            $modelInstansis = new Instansi();
            $result = $modelInstansis->getInstansis($value);
            $this->searchInstansiList = $result;
        } else {
            $this->searchInstansiList = [];
        }
    }

    public function selectInstansi($name, $address, $negara_id, $coordinates, $ptqs, $status, $badanKemitraan)
    {
        $this->nama_pihak = $name;
        $this->alamat_pihak = $address;
        $this->negara_pihak = $negara_id;
        $this->koordinat_pihak = $coordinates;
        $this->ptqs = $ptqs;
        $this->status = $status;
        $this->badanKemitraan = $badanKemitraan;
        $this->searchInstansiList = [];
    }

    public function closeEdit()
    {
        $this->showModalsEdit = false;
    }

    public function emitEdit()
    {
        $this->showModalsEdit = false;
    }

    public function delete($id)
    {
        $instansi = Instansi::where('id', '=', $id);
        $instansi->delete();
    }

    public function getEdit($id)
    {

        $this->showModalsEdit = true;
        $instansi = Instansi::where('id', '=', $id)->first();
        if ($instansi) {
            $this->status = $instansi->status ?? null;
            $this->nama_pihak = $instansi->name ?? null;
            $this->alamat_pihak = $instansi->address ?? null;
            $this->koordinat_pihak = $instansi->coordinates ?? null;
            $this->ptqs = $instansi->ptqs ?? 0;
            if (isset($instansi->badan_kemitraan) && is_numeric($instansi->badan_kemitraan)) {
                // If $instansi->badan_kemitraan is a number
                $this->badanKemitraan = $instansi->badan_kemitraan ?? 1;
            } else {
                // If $instansi->badan_kemitraan is not a number
                $this->badanKemitraan = 99;
                $this->lainnya = $instansi->badan_kemitraan;
            }


            $this->negara_pihak = $instansi->negara_id ?? 103;
        }
    }

    public function getReferenceCounts($searchProdi = null)
    {
        try {
            $instansis = Instansi::query();

            // Join with the "negaras" table to get the country name
            $instansis->leftJoin('negaras', 'instansis.negara_id', '=', 'negaras.id')
                ->addSelect('negaras.name as negara_name') // Add the negara_name column
                ->addSelect('instansis.name', 'instansis.address', 'instansis.ptqs', 'instansis.badan_kemitraan'); // Ensure other necessary columns are available

            // Filter by instansi type
            if ($this->instansiTipe) {
                if ($this->instansiTipe === 'dalam_negri') {
                    $instansis->where('instansis.negara_id', 103);
                } else {
                    $instansis->where('instansis.negara_id', '!=', 103);
                }
            }

            // Apply search filter based on instansi name and address
            if ($this->searchProdi) {
                $instansis->where(function ($query) {
                    $query->where('instansis.name', 'like', '%' . $this->searchProdi . '%') // Specify table for "name"
                        ->orWhere('instansis.address', 'like', '%' . $this->searchProdi . '%');
                });
            }

            // Filter by year if specified
            if ($this->tahun) {
                $instansis->whereYear('instansis.created_at', $this->tahun); // Specify table for "created_at"
            }

            // Sort by selected column and direction
            if ($this->orderBy === 'name') {
                $instansis->orderBy('instansis.name', $this->orderDirection);
            } else {
                $instansis->orderBy($this->orderBy, $this->orderDirection);
            }

            // Return the paginated result
            return $instansis->paginate($this->perPage);
        } catch (\Exception $e) {
            // Debug the error message
            dd('Error Message: ' . $e->getMessage(), 'Trace: ' . $e->getTraceAsString());
        }
    }

    public function render()
    {
        $referenceCounts = $this->getReferenceCounts(); // Ambil data dengan filter dan urutan

        return view('livewire.datatables.jumlah-mitra', [
            'referenceCounts' => $referenceCounts,
            'orderBy' => $this->orderBy,
            'orderByText' => $this->orderByText,
            'kerjasamaId' => $this->kerjasamaId,
            'kerjaSamaText' => $this->kerjaSamaText,
            'orderDirection' => $this->orderDirection,
            'orderDirectionText' => $this->orderDirectionText,
            'tahunText' => $this->tahunText,
            'availableYears' => $this->availableYears,
            'instansiTipeText' => $this->instansiTipeText,
            'jenjang' => $this->jenjang,
            'jenjangText' => $this->jenjangText,
        ]);
    }



    // Set parameter untuk Kerjasama ID
    public function setKerjasamaId($id, $text)
    {
        $this->kerjasamaId = $id;
        $this->kerjaSamaText = $text;
        $this->resetPage();
    }

    // Set parameter untuk kolom pengurutan
    public function setOrderBy($column, $text)
    {
        $this->orderBy = $column;
        $this->orderByText = $text;
        $this->resetPage();
    }

    // Toggle urutan (asc/desc)
    public function setOrderDirection()
    {
        $this->orderDirection = $this->orderDirection === "asc" ? "desc" : "asc";
        $this->updated();
    }

    // Set jumlah item per halaman
    public function setPerPage($number)
    {
        $this->perPage = $number;
        $this->resetPage();
    }

    // Set filter untuk tahun
    public function setTahun($tahun, $text)
    {
        $this->tahun = $tahun;
        $this->tahunText = $text;
        $this->resetPage();
    }

    // Get daftar tahun yang tersedia untuk dropdown
    protected function getAvailableYears()
    {
        $years = Instansi::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $years = array_filter($years, function ($year) {
            return $year != 0;
        });

        if (!in_array(2024, $years)) {
            $years[] = 2024;
        }

        rsort($years); // Urutkan dari yang terbesar ke terkecil
        return $years;
    }

    // Set filter untuk tipe instansi (dalam negri/luar negri)
    public function setInstansiTipe($type, $text)
    {
        $this->instansiTipe = $type;
        $this->instansiTipeText = $text;
        $this->resetPage();
    }
}
