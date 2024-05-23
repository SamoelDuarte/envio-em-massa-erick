<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $appends = [
        'phone',
        'location',
        'display_created_at'
    ];
    protected $fillable = [
        'name',
        'jid',
        'zipcode',
        'public_place',
        'neighborhood',
        'city',
        'state',
        'number',
        'created_at',
        'updated_at'
    ];


    public function getPhoneAttribute()
    {
        return explode('@', $this->jid)[0];
    }

    public function getDisplayCreatedAtAttribute()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    public function getLocationAttribute($number)
    {
        return 'CEP: ' . $this->zipcode . " \n " .
            'Logradouro: ' . $this->public_place . " \n " .
            'N° : ' . $this->number . " \n " .
            'Bairro: ' . $this->neighborhood . " \n " .
            'Cidade: ' . $this->city . " \n " .
            'Estado: ' . $this->state . " \n ";
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}
