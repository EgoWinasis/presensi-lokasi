<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tgl_mulai_cuti',
        'tgl_selesai_cuti',
        'jumlah_hari',
        'keterangan',
        'status_admin',
        'status_superadmin',
    ];

    // Relasi ke User (Pengguna yang mengajukan cuti)
    public function user()
    {
        return $this->belongsTo(User::class); // Relasi ke model User
    }
}
