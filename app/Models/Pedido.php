<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /*
        Schema::create('Pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('pais');
            $table->string('departamento');
            $table->string('departamento2');
            $table->string('distrito');
            $table->text('direccion');
            $table->text('direccion2');
            $table->string('telefono');
            $table->string('estado');
            $table->boolean('completado');
            $table->longText('productos');
            $table->float('subtotal');
            $table->float('delivery');
            $table->dateTime('fecha');

        });
    
    */

    protected $table = 'Pedidos';
    protected $fillable = ['nombre','apellido','username','pais','departamento',
    'departamento2','distrito','direccion','direccion2','telefono',
    'estado','completado','productos','subtotal','delivery','fecha','id_rastreo','paqueteria'];
    public $timestamps = false; 
    use HasFactory;
}
