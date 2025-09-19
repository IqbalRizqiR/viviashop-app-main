<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'shipping_adjusted_at' => 'datetime',
    ];

    public const CREATED = 'created';
	public const CONFIRMED = 'confirmed';
	public const DELIVERED = 'delivered';
	public const COMPLETED = 'completed';
	public const CANCELLED = 'cancelled';

	public const ORDERCODE = 'INV';

	public const PAID = 'paid';
	public const UNPAID = 'unpaid';
	public const WAITING = 'waiting';

	public const STATUSES = [
		self::CREATED => 'Created',
		self::CONFIRMED => 'Confirmed',
		self::DELIVERED => 'Delivered',
		self::COMPLETED => 'Completed',
		self::CANCELLED => 'Cancelled',
	];

    public static function integerToRoman($integer)
	{
		$integer = intval($integer);
		$result = '';

		// Create a lookup array that contains all of the Roman numerals.
		$lookup = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];

		foreach ($lookup as $roman => $value) {
			$matches = intval($integer/$value);
			$result .= str_repeat($roman, $matches);
			$integer = $integer % $value;
		}

		return $result;
	}

	public static function generateCode()
	{
		$now = Carbon::now();
		$dateCode = self::ORDERCODE . '-' . $now->format('d') . '-' . $now->format('m') . '-' . $now->format('Y') . '-' . $now->format('H') . '-' . $now->format('i') . '-' . $now->format('s');

		if (self::_isOrderCodeExists($dateCode)) {
			sleep(1);
			return self::generateCode();
		}

		return $dateCode;
	}

    private static function _isOrderCodeExists($orderCode)
	{
		return Order::where('code', '=', $orderCode)->exists();
	}

	public function orderItems()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function shipment()
	{
		return $this->hasOne(Shipment::class);
	}

	public function isPaid()
	{
		return $this->payment_status == self::PAID;
	}

    public function isCompleted()
	{
		return $this->status == self::COMPLETED;
	}

	public function isCancelled()
	{
		return $this->status == self::CANCELLED;
	}

	public function isConfirmed()
	{
		return $this->status == self::CONFIRMED;
	}

	public function isDelivered()
	{
		return $this->status == self::DELIVERED;
	}


	public function getCustomerFullNameAttribute()
	{
		return "{$this->customer_first_name} {$this->customer_last_name}";
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function shippingAdjustedBy()
	{
		return $this->belongsTo(User::class, 'shipping_adjusted_by');
	}

	public function scopeForUser($query, $user)
	{
		return $query->where('user_id', $user->id);
	}

	public function employeePerformance()
	{
		return $this->hasOne(EmployeePerformance::class);
	}

	public function isHandledByEmployee()
	{
		return $this->use_employee_tracking && !empty($this->handled_by);
	}

	public function isOfflineStoreOrder()
	{
		// Check if order was created by admin (offline store order)
		// Admin orders always have customer_last_name = "Toko"
		return $this->customer_last_name === 'Toko';
	}

	public function needsShipment()
	{
		if ($this->isOfflineStoreOrder()) {
			return false;
		}

		return $this->shipping_service_name !== 'Self Pickup';
	}

	public function isShippingCostAdjusted()
	{
		return $this->shipping_cost_adjusted;
	}

	public function hasOriginalShippingData()
	{
		return !is_null($this->original_shipping_cost) && 
			   !is_null($this->original_shipping_courier) && 
			   !is_null($this->original_shipping_service_name);
	}

	public function adjustShippingCost($newCost, $newCourier, $newServiceName, $note = null, $adjustedBy = null)
	{
		if (!$this->hasOriginalShippingData()) {
			$this->original_shipping_cost = $this->shipping_cost;
			$this->original_shipping_courier = $this->shipping_courier;
			$this->original_shipping_service_name = $this->shipping_service_name;
		}

		$oldGrandTotal = $this->grand_total;
		$shippingDifference = $newCost - $this->shipping_cost;

		$this->shipping_cost = $newCost;
		$this->shipping_courier = $newCourier;
		$this->shipping_service_name = $newServiceName;
		$this->grand_total = $oldGrandTotal + $shippingDifference;
		$this->shipping_cost_adjusted = true;
		$this->shipping_adjustment_note = $note;
		$this->shipping_adjusted_at = now();
		$this->shipping_adjusted_by = $adjustedBy;

		return $this->save();
	}

	public function getShippingCostDifference()
	{
		if (!$this->hasOriginalShippingData()) {
			return 0;
		}
		
		return $this->shipping_cost - $this->original_shipping_cost;
	}
}
