<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /*
        Schema::create('Producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->default('');
            $table->text('descripcion')->default('');
            $table->float('precio')->unique()->default('');
            $table->integer('inventario')->default(0);
            $table->text('categoria')->default('-');
            $table->string('color')->default('-');
            $table->string('talla')->default('U');
            $table->longText('imagen1')->default('https://i.imgur.com/i1K4XH8.png');
            $table->longText('imagen2')->default('https://i.imgur.com/i1K4XH8.png');
            $table->longText('imagen3')->default('https://i.imgur.com/i1K4XH8.png');
            $table->longText('imagen4')->default('https://i.imgur.com/i1K4XH8.png');
        });
    
    */

    protected $table = 'Producto';
    protected $fillable = ['nombre','descripcion','precio','inventario','categoria','color','talla','imagen1','imagen2','imagen3','imagen4'];
    public $timestamps = false;
    use HasFactory;
}
