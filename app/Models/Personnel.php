<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model {
    
        protected $table = 'personnel';
        protected $primaryKey = 'id';
        public $timestamps = false;
        protected $fillable = [
            'ext_emp_id',
            'full_name',
            'emp_status',
            'created_by',
            'updated_by',
            'dt_updated'
        ];
}