<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineReports extends Model
{
    use HasFactory;

   public function user(){
        return $this->hasOne(User::class, 'directory_id','directory_id');
   }
   
   public function dates(){
        return $this->belongsTo(OfflineReportsDates::class, 'offline_reports_id','id');
   }

}
