<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['user_id','notification_type','message','read','auditlogid'];

}
