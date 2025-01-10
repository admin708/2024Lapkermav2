<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Instansi extends Model
{
    use HasFactory;
    protected $table = "instansis";
    protected $fillable = ['name', 'address', 'negara_id', 'coordinates', 'ptqs', 'status', 'badan_kemitraan'];

    public function getNegara()
    {
        return $this->belongsTo(Negara::class, "negara_id");
    }

    public function getInstansis($instansiName)
    {
        return self::select('id', 'name') // Select only the id and name columns
        ->where('name', 'like', '%' . $instansiName . '%')->orderBy('id', 'desc')->limit(10)->get();
    }
}
