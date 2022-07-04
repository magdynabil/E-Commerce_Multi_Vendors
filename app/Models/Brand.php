<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Translatable;
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];


    protected $translatedAttributes = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['is_active','photo'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function scopeActive($query){
        return $query -> where('is_active',1) ;
    }
    public function getActive(){
        return  $this -> is_active  == 0 ?  'غير مفعل'   : 'مفعل' ;
    }
}
