<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoaPenggiat extends Model
{
    protected $table = 'moa_penggiat';
    protected $fillable = ['id', 'id_lapkerma', 'pihak', 'id_pihak', 'id_pj', 'id_pejabat', 'fakultas_pihak', 'prodi'];

    use HasFactory;

    public function getMoa()
    {
        return $this->belongsTo(instansi::class, "id_pihak");
    }

    public function getPejabat()
    {
        return $this->belongsTo(Pejabat::class, "id_pejabat");
    }

    public function getPenanggungjawab()
    {
        return $this->belongsTo(PenanggungJawab::class, "id_pj");
    }
}
