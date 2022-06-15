<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZakatFitrahReceived extends Model
{
    use SoftDeletes;
    protected $table = 'service_zakat_received';
    protected $fillable = ['id_user', 'id_peserta', 'year_hijriah', 'amount_received', 'notes', 'is_zakat_received', 'id_user_zakat_received', 'id_user_amount_received_updated', 'date_zakat_received', 'deleted_at'];
    /**
     * Get the user that owns the ZakatFitrah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function id_peserta_peserta()
    {
        return $this->belongsTo(PesertaModel::class, 'id_peserta');
    }
    public function id_user_service_zakat()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function id_user_zakat_sent_users()
    {
        return $this->belongsTo(User::class, 'id_user_zakat_sent');
    }
    public function id_user_zakat_received_users()
    {
        return $this->belongsTo(User::class, 'id_user_zakat_received');
    }
    public function id_user_amount_sent_updated_users()
    {
        return $this->belongsTo(User::class, 'id_user_amount_sent_updated');
    }
    public function id_user_amount_received_updated_users()
    {
        return $this->belongsTo(User::class, 'id_user_amount_received_updated');
    }
}
