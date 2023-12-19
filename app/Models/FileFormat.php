<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FileFormat extends Model
{
    use HasFactory;
    protected $table = 'file_format_info' ;
    protected $fillable = [
        'id','file_format','vendor','fileName','object'
    ];
}
