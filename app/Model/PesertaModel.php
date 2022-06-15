<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesertaModel extends Model
{
    use SoftDeletes;
    protected $table = 'peserta';
    protected $fillable = ['id', 'name', 'is_person', 'service_money', 'service_zakat_sent', 'service_zakat_received', 'service_qurban_received', 'service_qurban_sent', 'url_qrcode', 'notes', 'phone', 'location', 'deleted_at'];
}
