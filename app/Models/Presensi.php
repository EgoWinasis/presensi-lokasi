<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    // Specify the table name (optional if the table name is the plural form of the model name)
    protected $table = 'presensis';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'user_id',
        'tgl',
        'jam_masuk',
        'foto_masuk',
        'lokasi_masuk',
        'ket_masuk',
        'jam_keluar',
        'foto_keluar',
        'lokasi_keluar',
        'ket_keluar'
    ];

    // Define the casting of certain attributes (e.g., for location fields)
    protected $casts = [
        'lokasi_masuk' => 'array', // JSON cast
        'lokasi_keluar' => 'array', // JSON cast
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
