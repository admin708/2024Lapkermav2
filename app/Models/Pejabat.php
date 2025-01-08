<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    protected $table = 'pejabat';
    use HasFactory;
    protected $fillable = ['nama', 'jabatan'];
    public $timestamps = false;

    public function getPejabat($pejabatName)
    {
        return self::select('id','nama')->where('nama', 'like', '%' . $pejabatName . '%')->limit(5)->get();
    }
}
