<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ZakatFitrahSent extends Model
{
    use SoftDeletes;
    protected $table = 'service_zakat_sent';
    protected $fillable = ['id_user','id_peserta','year_hijriah','amount_sent','notes','is_zakat_sent','id_user_zakat_sent','id_user_amount_sent_updated','date_zakat_sent'];
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
    public function id_user_amount_sent_updated_users()
    {
        return $this->belongsTo(User::class, 'id_user_amount_sent_updated');
    }
}
