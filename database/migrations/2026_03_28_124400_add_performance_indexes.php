<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Performance indexes for API-first architecture.
     * These indexes optimize the most common query patterns across all API endpoints.
     */
    public function up(): void
    {
        // ─── Orders: Most queried table ────────────────────────────
        Schema::table('orders', function (Blueprint $table) {
            // Dashboard revenue queries: WHERE payment_status = 'paid' AND created_at >= ?
            $table->index(['payment_status', 'created_at'], 'idx_orders_payment_created');

            // Admin order listing: WHERE status = ? ORDER BY order_date DESC
            $table->index(['status', 'order_date'], 'idx_orders_status_date');

            // Customer order listing: WHERE user_id = ? ORDER BY created_at DESC
            $table->index(['user_id', 'created_at'], 'idx_orders_user_created');

            // Search by order code
            $table->index('code', 'idx_orders_code');
        });

        // ─── Order Items: Frequently joined with orders ────────────
        Schema::table('order_items', function (Blueprint $table) {
            // Report COGS calculation: JOIN orders GROUP BY product_id
            $table->index(['order_id', 'product_id'], 'idx_order_items_order_product');

            // Top products query: GROUP BY product_id, SUM(qty)
            $table->index('product_id', 'idx_order_items_product');
        });

        // ─── Products: Catalog queries ─────────────────────────────
        Schema::table('products', function (Blueprint $table) {
            // Public product listing: WHERE status = 1 AND parent_id IS NULL AND type = ?
            $table->index(['status', 'parent_id', 'type'], 'idx_products_status_parent_type');

            // Product search: LIKE on name, sku
            $table->index('sku', 'idx_products_sku');

            // Barcode lookup
            $table->index('barcode', 'idx_products_barcode');

            // Brand filter
            $table->index('brand_id', 'idx_products_brand');

            // Slug-based lookup for product detail page
            $table->index('slug', 'idx_products_slug');
        });

        // ─── Product Variants: Stock & lookup queries ──────────────
        Schema::table('product_variants', function (Blueprint $table) {
            // Variant listing for a product: WHERE product_id = ? AND is_active = 1
            $table->index(['product_id', 'is_active'], 'idx_variants_product_active');

            // SKU lookup
            $table->index('sku', 'idx_variants_sku');
        });

        // ─── Product Inventories: Stock queries ────────────────────
        Schema::table('product_inventories', function (Blueprint $table) {
            // Low stock detection: WHERE qty <= 5
            $table->index(['product_id', 'qty'], 'idx_inventories_product_qty');
        });

        // ─── Wishlists: User lookup ────────────────────────────────
        Schema::table('wish_lists', function (Blueprint $table) {
            // User's wishlist: WHERE user_id = ?
            $table->index(['user_id', 'product_id'], 'idx_wishlists_user_product');
        });

        // ─── Employee Performance: Dashboard aggregation ───────────
        Schema::table('employee_performances', function (Blueprint $table) {
            // Monthly grouping: WHERE completed_at >= ? GROUP BY employee_name
            $table->index(['employee_name', 'completed_at'], 'idx_employee_perf_name_date');
        });

        // ─── Shipments: Order lookup ───────────────────────────────
        Schema::table('shipments', function (Blueprint $table) {
            $table->index('order_id', 'idx_shipments_order');
        });

        // ─── Product Images: Product lookup ────────────────────────
        Schema::table('product_images', function (Blueprint $table) {
            $table->index('product_id', 'idx_product_images_product');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_payment_created');
            $table->dropIndex('idx_orders_status_date');
            $table->dropIndex('idx_orders_user_created');
            $table->dropIndex('idx_orders_code');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_order_items_order_product');
            $table->dropIndex('idx_order_items_product');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_status_parent_type');
            $table->dropIndex('idx_products_sku');
            $table->dropIndex('idx_products_barcode');
            $table->dropIndex('idx_products_brand');
            $table->dropIndex('idx_products_slug');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex('idx_variants_product_active');
            $table->dropIndex('idx_variants_sku');
        });

        Schema::table('product_inventories', function (Blueprint $table) {
            $table->dropIndex('idx_inventories_product_qty');
        });

        Schema::table('wish_lists', function (Blueprint $table) {
            $table->dropIndex('idx_wishlists_user_product');
        });

        Schema::table('employee_performances', function (Blueprint $table) {
            $table->dropIndex('idx_employee_perf_name_date');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex('idx_shipments_order');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex('idx_product_images_product');
        });
    }
};
