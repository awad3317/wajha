<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountCouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. كوبون صالح للاستخدام (جميع الشروط متاحة)
        DB::table('discount_coupons')->insert([
            'code' => 'VALID2023',
            'description' => 'كوبون صالح للاستخدام مع جميع المنشآت',
            'discount_type' => 'percentage',
            'discount_value' => 15.00,
            'start_date' => $now->copy()->subDays(5),
            'end_date' => $now->copy()->addMonth(),
            'max_uses' => 100,
            'current_uses' => 25,
            'is_active' => true,
            'applies_to' => 'all_establishments',
            'created_by' => 1, // افتراضي أن أول مستخدم هو المدير
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 2. كوبون منتهي الصلاحية
        DB::table('discount_coupons')->insert([
            'code' => 'EXPIRED2023',
            'description' => 'كوبون انتهت صلاحيته',
            'discount_type' => 'fixed_amount',
            'discount_value' => 50.00,
            'start_date' => $now->copy()->subMonth(),
            'end_date' => $now->copy()->subDays(1),
            'max_uses' => 50,
            'current_uses' => 10,
            'is_active' => true,
            'applies_to' => 'specific_establishments',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. كوبون تم استنفاذ عدد استخداماته
        DB::table('discount_coupons')->insert([
            'code' => 'MAXEDOUT2023',
            'description' => 'كوبون تم الوصول للحد الأقصى لاستخداماته',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'start_date' => $now->copy()->subDays(10),
            'end_date' => $now->copy()->addMonth(),
            'max_uses' => 30,
            'current_uses' => 30, // مساوي للحد الأقصى
            'is_active' => true,
            'applies_to' => 'specific_types',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('discount_coupons')->insert([
            'code' => 'INACTIVE2023',
            'description' => 'كوبون غير مفعل حالياً',
            'discount_type' => 'fixed_amount',
            'discount_value' => 25.00,
            'start_date' => $now->copy()->subDays(5),
            'end_date' => $now->copy()->addMonth(),
            'max_uses' => 200,
            'current_uses' => 45,
            'is_active' => false, // غير مفعل
            'applies_to' => 'all_establishments',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('discount_coupons')->insert([
            'code' => 'TYPEONLY2023',
            'description' => 'كوبون صالح فقط لأنواع محددة من المنشآت',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'start_date' => $now->copy()->subDays(2),
            'end_date' => $now->copy()->addWeeks(3),
            'max_uses' => 75,
            'current_uses' => 15,
            'is_active' => true,
            'applies_to' => 'specific_types',
            'created_by' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

    }
}
