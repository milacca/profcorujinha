<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment', // Comentário da avaliação
        'rating',  // Avaliação em estrelas (pode ser um número de 1 a 5)
        'product_id', // Chave estrangeira para o produto avaliado
    ];

        public function product()
    {
        return $this->belongsTo(Product::class);
    }

        public function user()
    {
        return $this->belongsTo(User::class);
    }

}
