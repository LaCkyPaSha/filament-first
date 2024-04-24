<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'attachment',
        'status_id',
        'deadline'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'task-user')->withTimestamps();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
