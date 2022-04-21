<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QurbanSent extends Model
{
    use SoftDeletes;
    protected $table = 'service_qurban_sent';
    protected $fillable = ['id_user', 'id_peserta', 'year_hijriah', 'amount_sent', 'amount_type', 'notes', 'is_qurban_sent', 'id_user_qurban_sent', 'id_user_amount_sent_updated', 'date_qurban_sent'];
    /**
     * Get the user that owns the qurbanFitrah
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function id_peserta_peserta()
    {
        return $this->belongsTo(PesertaModel::class, 'id_peserta');
    }
    public function id_user_service_qurban()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function id_user_qurban_sent_users()
    {
        return $this->belongsTo(User::class, 'id_user_qurban_sent');
    }
    public function id_user_amount_sent_updated_users()
    {
        return $this->belongsTo(User::class, 'id_user_amount_sent_updated');
    }
}
