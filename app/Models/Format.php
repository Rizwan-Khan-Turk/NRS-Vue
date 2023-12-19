<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Format extends Model
{
    use HasFactory;
    protected $table = 'format';
    protected $fillable = [
        'format_name','format_value'
    ];
}
