<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class Cliente extends Model
{
    use HasApiTokens;
    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'password'
    ];
}
