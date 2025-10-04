<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AnonymizedMatricules extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'anonymized_id'
    ];

    protected $casts = [
        'matricule' => 'string',
        'anonymized_id' => 'integer'
    ];
}
