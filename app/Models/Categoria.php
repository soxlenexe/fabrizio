<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /*
        Schema::create('Categoria', function (Blueprint $table) {
            $table->id();
            $table->string('categoria_id')->unique();
        });
    
    */
    protected $table = 'Categoria';
    protected $fillable = ['categoria_id'];
    public $timestamps = false;
    use HasFactory;
}
