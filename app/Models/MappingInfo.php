<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MappingInfo extends Model
{
    use HasFactory;
    protected $table = 'mapping_info' ;
    protected $fillable = [
        'Attribute','Length','Leftpad','Rightpad','file_format','vendor','date_format','start_end_index'
    ];
}
