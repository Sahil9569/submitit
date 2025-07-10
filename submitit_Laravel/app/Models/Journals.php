<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journals extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function journal_scores()
    {
        return $this->hasOne(JournalScore::class, 'journal_id');
    }
}
