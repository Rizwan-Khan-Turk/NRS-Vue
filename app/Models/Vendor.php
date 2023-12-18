<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id','vname', 'vcode', 'po_ip', 'po_port', 'po_directory',
        'invoice_ip', 'invoice_port', 'invoice_directory', 'status','ftp_username','ftp_password','file_pattern','ftp_username_invoice','ftp_password_invoice'
    ];
}
