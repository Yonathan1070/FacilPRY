<?php

namespace App\Models\Utilitarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Correo extends Model
{
    public static function enviarCorreo($vista, $data, $from, $to, $subject){
        Mail::send($vista, $data, function($message) use($from, $to, $subject){
            $message->from($from)
                ->to($to)
                ->subject($subject);
        });
        
        if (Mail::failures()) {
            return false;
        }
        return true;
    }
}
