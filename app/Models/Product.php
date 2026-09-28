<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public const TYPE_MOBILE = 'mobile';
    public const TYPE_ACCESSORY = 'accessory';
    public const TYPE_SERVICE = 'service';

    public const TRACK_NONE = 'none';
    public const TRACK_QUANTITY = 'quantity';
    public const TRACK_SERIAL = 'serial';

    protected $fillable = [
        'branch_id', 'category_id', 'subcategory_id', 'brand_id', 'name', 'sku', 'barcode',
        'product_type', 'tracking_type', 'default_warranty_days', 'sale_price', 'wholesale_price',
        'min_price', 'is_active', 'qty_on_hand', 'avg_cost', 'notes', 'ram', 'storage', 'color',
        'pta_status', 'network_status', 'condition', 'warranty_type', 'imei_mode', 'woocommerce_product_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'default_warranty_days' => 'integer',
            'sale_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'min_price' => 'decimal:2',
            'qty_on_hand' => 'integer',
            'avg_cost' => 'decimal:2',
        ];
    }

    public function units(): HasMany { return $this->hasMany(Unit::class); }
    public function movements(): HasMany { return $this->hasMany(StockMovement::class); }
    public function usesUnits(): bool { return $this->tracking_type === self::TRACK_SERIAL || $this->product_type === self::TYPE_MOBILE; }
    public function usesQuantity(): bool { return $this->tracking_type === self::TRACK_QUANTITY && $this->product_type !== self::TYPE_SERVICE; }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function subcategory(): BelongsTo { return $this->belongsTo(Subcategory::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public static function types(): array { return [self::TYPE_MOBILE => 'Mobile', self::TYPE_ACCESSORY => 'Accessory', self::TYPE_SERVICE => 'Service']; }
    public static function trackingTypes(): array { return [self::TRACK_NONE => 'None', self::TRACK_QUANTITY => 'Quantity', self::TRACK_SERIAL => 'Serial']; }
    public static function ptaStatuses(): array { return ['APPROVED' => 'PTA approved', 'NON_PTA' => 'Non-PTA', 'PENDING' => 'Pending']; }
    public static function networkStatuses(): array { return ['UNLOCKED' => 'Factory unlock', 'JV' => 'JV', 'FACTORY_LOCK' => 'Factory lock', 'CARRIER_LOCK' => 'Carrier lock']; }
    public static function conditions(): array { return ['NEW' => 'New', 'USED' => 'Used']; }
    public static function warrantyTypes(): array { return ['NONE' => 'No warranty', 'SHOP' => 'Shop warranty', 'BRAND' => 'Brand warranty']; }
    public static function imeiModes(): array { return ['single' => 'Single IMEI', 'dual' => 'Dual IMEI']; }
}
