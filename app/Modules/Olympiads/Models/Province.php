<?php

namespace App\Modules\Olympiads\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    protected $table = 'province';
    protected $primaryKey = 'province_id';
    public $timestamps = false;

    protected $fillable = [
        'province_name',
        'department_id',
    ];


    public function setProvinceNameAttribute($value)
    {
        $this->attributes['province_name'] = strtoupper($value);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function school()
    {
        return $this->hasMany(School::class, 'province_id');
    }
}
