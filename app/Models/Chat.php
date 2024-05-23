<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Switch_;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'service_id',
        'jid',
        'active',
        'erro',
    ];

    protected $appends = [
        'display_status'
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'jid', 'jid');
    }

    public function getDisplayStatusAttribute(){
        
        if($this->await_answer == "await_human"){
          
        }
        switch ($this->await_answer) {
            case 'await_human':
                return "Aguardando Atendimento";
                break;

                case 'in_service':
                    return "Em Atendimento";
                    break;

                    case 'finish':
                        return "Finalizado";
                        break;
            
            default:
            return "Sem Status";
                break;
        }
    }
}
