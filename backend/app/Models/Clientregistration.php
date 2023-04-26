<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientregistration extends Model
{
    use HasFactory;

    protected $table = 'client_registrations';

    protected $fillable = [
        'id',
        'clienttype',
        'congregation',
        'province',
        'product',
        'place',
        'financialyear',
        'clientcode',
        'pi',
        'projectvalue',
        'amcvalue',
        'gst',
        'amcvalue',
        'total',
        'paid',
        'balance',
        'status'
    ];
}
