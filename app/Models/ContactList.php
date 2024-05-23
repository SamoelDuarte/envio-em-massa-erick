<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    use HasFactory;

    protected $table = 'contact_list';

    /**
     * Get the contact that owns the contact list.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
