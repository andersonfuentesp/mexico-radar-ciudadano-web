<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileUser extends Model
{
    use HasFactory;
    
    // Especifica la tabla asociada
    protected $table = 'mobile_users';

    // Llave primaria de la tabla
    protected $primaryKey = 'user_id';

    protected $guarded = [];
}
