<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
  use HasFactory;

  protected $fillable = [
    'title', 'todo_list_id'
  ];

  /**
   * Get Cards of Todo Lists
   */
  public function todoList() {
    return $this->belongsTo('App\Models\TodoList');
  }

}
