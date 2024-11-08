<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable=[
        'name','parent_id','description','image','slug','status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id','id')
            ->withDefault([
                'name'=>'-'
            ]);
    }
    public function children()
    {
        return $this->hasMany(Category::class,'parent_id','id');
    }
    public function scopeActive(Builder $builder)
    {
        $builder->where('status','=','active');
    }

    public function scopeFilter(Builder $builder,$filters)
    {
        $builder->when($filters['name'] ?? false, function ($builder, $value){

            $builder->where('name','LIKE',"%{value}%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value){

            $builder->where('status','=',$value);
        });

    }
    public static function rules($id=0){

        return [
            'name'=>[
                'required',
                'string',
                'min:3',
                'max:255',
                 Rule::unique('categories','name')->ignore($id)
                ],
            'parent_id'=>[
                'nullable','int','exists:categories,id'
            ],
            'image'=>[
                'image','max:1048576','dimensions:width=100,height=100'
            ],
            'status'=>'in:active,archived'
        ];
    }
}
