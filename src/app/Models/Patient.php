<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'user_id',
        'usia_ibu',
        'berat_badan',
        'tinggi_badan',
        'usia_kehamilan_minggu',
        'golongan_darah',
    ];

    /**
     * Relasi: Patient milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
