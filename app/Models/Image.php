<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    // Define qué campos se pueden asignar masivamente (opcional si usas new Image y save())
    protected $fillable = [
        'path',
        'user_id',
        'description',
    ];

    /**
     * Define la relación: una imagen pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}