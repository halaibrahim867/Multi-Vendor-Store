<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\Uid\AbstractUid;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'category_id',
        'store_id', 'price', 'compare_price', 'status'
    ];

    protected $hidden=[
      'created_at','image','updated_at','deleted_at' //not returned in api response
    ];

    protected $appends=[
        'image_url'
    ];

    public static function booted()
    {
        static::addGlobalScope('store', new  StoreScope());

        static::creating(function (Product $product){
            $product->slug=Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'product_tag',
            'product_id',
            'tag_id',
            'id',
            'id'
        );
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://th.bing.com/th/id/OIP.mhEjokf4cHBCeCsOqohUdwHaHa?w=174&h=180&c=7&r=0&o=5&pid=1.7';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);

    }

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }
        return round(100 - (100 * ($this->price / $this->compare_price)));
    }

    public function scopeFilter(Builder $builder, $filter)
    {
        $options=array_merge([
            'store_id'=>null,
            'category_id'=>null,
            'tag_id'=>null,
            'status'=>'active'
        ],$filter);

        $builder->when($options['status'],function ($builder, $value){
            $builder->where('status',$value);
        });
        $builder->when($options['store_id'],function ($builder,$value){
            $builder->where('store_id',$value);
        });
        $builder->when($options['category_id'],function ($builder,$value){
            $builder->where('category_id',$value);
        });


        $builder->when($options['tag_id'],function ($builder,$value){

            $builder->whereExists(function ($query) use ($value){
                $query->select(1)
                    ->from('product_tag')
                    ->whereRaw('product_id = products.id')
                    ->where('tag_id',$value);
            });

            /*$builder->whereHas('tags',function ($builder) use ($value){
                $builder->where('tag_id',$value);
            });*/
        });
    }

}
