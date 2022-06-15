<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Celengan extends Model
{
    use SoftDeletes;
    protected $table = 'service_money';
    protected $fillable = ['id_peserta','id_user','year_hijriah','amount','notes','is_money_received','is_money_box_sent','date_money_received','date_money_box_sent','id_user_money_box_sent','id_user_money_received','id_user_amount_updated'];
    public function id_peserta_peserta()
    {
        return $this->belongsTo(PesertaModel::class, 'id_peserta');
    }
    public function id_user_service_money()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function id_user_money_received_users()
    {
        return $this->belongsTo(User::class, 'id_user_money_received');
    }
    public function id_user_money_box_sent_users()
    {
        return $this->belongsTo(User::class, 'id_user_money_box_sent');
    }
    public function id_user_amount_updated_users()
    {
        return $this->belongsTo(User::class, 'id_user_amount_updated');
    }
}
