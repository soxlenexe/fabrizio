<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /*
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->default('');
            $table->string('password')->default('');
            $table->string('username')->unique()->default('');
            $table->text('name')->default('');
            $table->text('last_name')->default('');
            $table->boolean('admin')->default(false);
        });
    
    */
    protected $table = 'users';
    protected $fillable = ['email','password','username','name','last_name','admin','telefono','signo'];
    public $timestamps = false;
    use HasFactory;
}
