<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeecounthistory extends Model
{
    use HasFactory;
    protected $table = 'employeecounthistory';
    
    protected $fillable = ['user_id', 'total_employee_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
