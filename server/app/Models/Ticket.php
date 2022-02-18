<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ["person_id", "queue_id", "priority", "state", "details"];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }
}
