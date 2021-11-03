<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
  use HasFactory;

  protected $fillable = [
    'title'
  ];

  /**
   * Get Cards of Todo Lists
   */
  public function cards() {
    return $this->hasMany('App\Models\Card');
  }

}
