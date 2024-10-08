<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;

class RoleVerifikasi extends Model
{
    use HasFactory;

    protected $table = 'role_verifikasi';
    protected $guarded = ['id'];

    public function ajuan()
    {
        return $this->belongsTo(Ajuan::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
