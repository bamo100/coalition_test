<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //fields that are meant to be filled/coming from the user
    protected $fillable = ['name', 'quantity_in_stock', 'price_per_item'];
}
