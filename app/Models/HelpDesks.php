<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpDesks extends Model
{
    use HasFactory;
    protected $fillable=[
        'emp_id', 'category', 'subject', 'description', 'file_path', 'cc_to', 'priority','status','mail','mobile','distributor_name','selected_equipment','active_comment'
     ];
     protected $casts = [
        'active_comment' => 'array',  // Ensure the active_comment is cast to an array
    ];
    public function emp()
    {
        return $this->belongsTo(EmployeeDetails::class, 'emp_id', 'emp_id');
    }
    

}
