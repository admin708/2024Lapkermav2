<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawab extends Model
{
    protected $table = 'penanggungjawab';
    use HasFactory;
    protected $fillable = ['name', 'designation', 'email', 'phone_number'];
    public $timestamps = false;


    public function getPJ($pjName)
    {
        return self::select("id","name")->where('name', 'like', '%' . $pjName . '%')->limit(5)->get();
    }
}
