<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'whatsapp_id',
        'address'
    ];

    public function wa_messages()
    {
        return $this->hasMany(Wa_message::class);
    }

    public function interest_logs()
    {
        return $this->hasMany(Interest_logs::class);
    }


    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
