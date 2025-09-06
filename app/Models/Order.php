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

	public function scopeForUser($query, $user)
	{
		return $query->where('user_id', $user->id);
	}

	public function isOfflineStoreOrder()
	{
		// Check if order was created by admin (offline store order)
		// Admin orders always have customer_last_name = "Toko"
		return $this->customer_last_name === 'Toko';
	}

	public function needsShipment()
	{
		// Offline store orders (created by admin) never need shipment
		if ($this->isOfflineStoreOrder()) {
			return false;
		}

		// Online orders need shipment unless they are self pickup
		return $this->shipping_service_name !== 'Self Pickup';
	}
}
