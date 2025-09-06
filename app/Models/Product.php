<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public const DRAFT = 0;
	public const ACTIVE = 1;
	public const INACTIVE = 2;

	public const STATUSES = [
		self::DRAFT => 'draft',
		self::ACTIVE => 'active',
		self::INACTIVE => 'inactive',
	];

	public const SIMPLE = 'simple';
	public const CONFIGURABLE = 'configurable';
	public const TYPES = [
		self::SIMPLE => 'Simple',
		self::CONFIGURABLE => 'Configurable',
	];
	
	/**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }
    
    public static function statuses()
	{
		return self::STATUSES;
	}
	
	public function statusLabel()
	{
		$statuses = $this->statuses();
		
		return isset($this->status) ? $statuses[$this->status] : null;
	}
    
    public static function types()
	{
		return self::TYPES;
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class, 'product_categories');
	}

	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

	public function variants()
	{
		return $this->hasMany(Product::class, 'parent_id')->orderBy('price', 'ASC');
	}

	public function productVariants()
	{
		return $this->hasMany(ProductVariant::class)->orderBy('price', 'ASC');
	}

	public function activeVariants()
	{
		return $this->hasMany(ProductVariant::class)->where('is_active', true)->orderBy('price', 'ASC');
	}

	public function productInventory()
	{
		return $this->hasOne(ProductInventory::class);
	}

	public function productImages()
	{
		return $this->hasMany(ProductImage::class);
	}

	public function scopeActive($query)
	{
		return $query->where('status', 1)
			->where('parent_id', null);
	}

	public function scopeFeatured($query)
	{
		return $query->where('is_featured', true);
	}

	public function scopeWithStock($query)
	{
		return $query->where(function ($q) {
			$q->where('type', 'simple')
			  ->whereHas('productInventory', function ($inv) {
				  $inv->where('qty', '>', 0);
			  })
			  ->orWhere('type', 'configurable')
			  ->whereHas('productVariants', function ($variants) {
				  $variants->where('stock', '>', 0)->where('is_active', true);
			  });
		});
	}

	public function scopePopular($query, $limit = 10)
	{
		$month = Carbon::now()->format('m');

		return $query->selectRaw('products.*, COUNT(order_items.id) as total_sold')
			->join('order_items', 'order_items.product_id', '=', 'products.id')
			->join('orders', 'order_items.order_id', '=', 'orders.id')
			->whereRaw(
				'orders.status = :order_satus AND MONTH(orders.order_date) = :month',
				[
					'order_status' => Order::COMPLETED,
					'month' => $month
				]
			)
			->groupBy('products.id')
			->orderByRaw('total_sold DESC')
			->limit($limit);
	}

	public function priceLabel()
	{
		if ($this->type == 'configurable' && $this->productVariants->count() > 0) {
			$minPrice = $this->productVariants->min('price');
			$maxPrice = $this->productVariants->max('price');
			
			if ($minPrice == $maxPrice) {
				return $minPrice;
			}
			
			return "Mulai dari " . number_format($minPrice, 0, ',', '.');
		}
		
		return ($this->variants->count() > 0) ? $this->variants->first()->price : $this->price;
	}

	public function getBasePriceAttribute()
	{
		if ($this->type == 'configurable' && $this->productVariants->count() > 0) {
			return $this->productVariants->min('price');
		}
		
		return $this->price;
	}

	public function getTotalStockAttribute()
	{
		if ($this->type == 'configurable') {
			return $this->productVariants->sum('stock');
		}
		
		return $this->productInventory?->qty ?? 0;
	}

	public function updateBasePrice()
	{
		if ($this->type == 'configurable' && $this->productVariants->count() > 0) {
			$this->base_price = $this->productVariants->min('price');
			$this->total_stock = $this->productVariants->sum('stock');
			$this->save();
		}
	}

	public function getVariantOptions()
	{
		if ($this->type != 'configurable') {
			return collect();
		}

		$options = [];
		$variants = $this->activeVariants()->with('variantAttributes')->get();
		
		foreach ($variants as $variant) {
			foreach ($variant->variantAttributes as $attribute) {
				if (!isset($options[$attribute->attribute_name])) {
					$options[$attribute->attribute_name] = [];
				}
				
				if (!in_array($attribute->attribute_value, $options[$attribute->attribute_name])) {
					$options[$attribute->attribute_name][] = $attribute->attribute_value;
				}
			}
		}
		
		foreach ($options as $key => $values) {
			sort($options[$key]);
		}
		
		return collect($options);
	}

	public function configurable()
	{
		return $this->type == 'configurable';
	}

	public function parent()
	{
		return $this->belongsTo(Product::class, 'parent_id');
	}

	public function productAttributeValues()
	{
		return $this->hasMany(ProductAttributeValue::class, 'parent_product_id');
	}

	public function variantAttributeValues()
	{
		return $this->hasMany(ProductAttributeValue::class, 'product_id');
	}

	public function configurableAttributes()
	{
		return Attribute::where('is_configurable', true)->with(['attribute_variants.attribute_options'])->get();
	}
}
