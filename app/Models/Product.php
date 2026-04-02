<?php

namespace App\Models;

use App\Models\Packaging;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productCategory(){
        return $this->belongsTo(ProductCategory::class);
    }

    public function packaging(){
        return $this->belongsTo(Packaging::class);
    }
}
