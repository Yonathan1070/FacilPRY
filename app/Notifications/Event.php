<?php

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class Event extends Notification{
    use Queueable;

    public $thread;

    /**
     * Create a new notification instance.
     * 
     * @return void
     */
    public function __construct($thread){
        $this->thread=$thread;
    }

    /**
     * Get the notification's delivery channels.
     * 
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable){
        return['database','broadcast'];
    }

    /**
     * Get the array representation of the notification.
     * 
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable){
        return[
            'thread'=>$this->thread,
            'user'=>auth()->user()
        ];
    }

    /**
     * Get the array representation of the notification.
     * 
     * @param mixed $notifiable
     * @return array
     */
    public function toBroadcast($notifiable){
        return new BroadcastMessage([
            'thread'=>$this->thread,
            'user'=>auth()->user()
        ]);
    }

    /**
     * Get the array representation of the notification.
     * 
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable){
        return[
            //
        ];
    }
}