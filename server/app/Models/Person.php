<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable =["name", "phone", "priority_id"];
    protected $hidden =  ["created_at", "updated_at"];

    public function queues(){
        return $this->belongsToMany(Queue::class, 'tickets');
    }

    public function priority(){
        return $this->belongsTo(Priority::class);
    }
}
