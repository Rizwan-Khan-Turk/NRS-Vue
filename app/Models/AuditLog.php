<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'auditlog';
    protected $fillable = ['object','vendor','transactionType','error_detail','status','file_name','file_content','po_number'];

}
