<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'sa_id_number',
        'mobile_number',
        'email',
        'birth_date',
        'language_code',
        'interests',
        'created_user_id',
        'updated_user_id',
    ];

    protected $casts = [
        'interests' => 'array',
        'birth_date' => 'datetime',
    ];
}
