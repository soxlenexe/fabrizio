<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoReserva extends Model
{
    /*
        Schema::create('ProductoReserva', function (Blueprint $table) {
            $table->id();
            $table->integer('producto_id');
            $table->string('username');
            $table->dateTime('fecha')->default(date_format(new DateTime(),'Y-m-d H:i:s'));
            $table->string('nombre')->default('');
            $table->float('precio')->default(0);
            $table->integer('cantidad')->default(0);
            $table->text('categoria')->default('-');
            $table->string('color')->default('-');
            $table->string('talla')->default('U');

        });
        });
    
    */

    protected $table = 'ProductoReserva';
    protected $fillable = ['producto_id','username','fecha','nombre','precio','cantidad','categoria','color','talla'];
    public $timestamps = false;
    use HasFactory;
}
