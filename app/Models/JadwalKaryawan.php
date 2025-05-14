<?php

// app/Models/JadwalKaryawan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKaryawan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tgl', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
