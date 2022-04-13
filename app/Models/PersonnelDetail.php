<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelDetail extends Model {
        
        protected $table = 'personnel_details';
        protected $primaryKey = 'id';
        public $timestamps = false;
        protected $fillable = [
            'personnel_id',
            'username',
            'phone_number',
            'pn_extension',
            'picture_url',
            'email'
        ];
}