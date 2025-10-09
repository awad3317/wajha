<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DiscountCoupon;
use App\Models\Establishment;
use App\Models\EstablishmentType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\AdminLoggerService;
use Livewire\WithPagination;


class DiscountCoupons extends Component
{
    use WithPagination;

    public $code, $description, $discount_type = 'percentage', $discount_value;
    public $start_date, $end_date, $max_uses, $is_active = true;
    public $applies_to = 'all_establishments';
    public $selectedEstablishments = [], $selectedTypes = [];

    public $isEdit = false, $showForm = false;
    public $deleteId = null, $deleteTitle = null;
    public $search = '', $selectedStatu = null;

    public $couponBeingEdited;

    protected $queryString = ['search', 'selectedStatu'];
    public $establishments = [];
    public $establishmentTypes = [];

    public function mount()
    {
        // $this->start_date = now()->format('Y-m-d\TH:i');
        // $this->end_date = now()->addMonth()->format('Y-m-d\TH:i');

        $this->establishments = Establishment::orderBy('name')->get();
        $this->establishmentTypes = EstablishmentType::orderBy('name')->get();

    }

  
    protected function rules()
    {
        return [
            'code' => 'required|string|unique:discount_coupons,code',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'applies_to' => 'required|in:all_establishments,specific_establishments,specific_types',
        ];
    }
    public function messages()
    {
        return [
            'code' => 'مطلوب|نصي|فريد في جدول كوبونات الخصم',
            'discount_type' => 'مطلوب|يجب أن يكون أحد: نسبة مئوية أو مبلغ ثابت',
            'discount_value' => 'مطلوب|رقم|يجب أن يكون أكبر من أو يساوي الصفر',
            'start_date' => 'مطلوب|تاريخ',
            'end_date' => 'مطلوب|تاريخ|يجب أن يكون بعد تاريخ البداية',
            'max_uses' => 'مطلوب|عدد صحيح|يجب أن يكون على الأقل 1',
            'is_active' => 'قيمة منطقية (نعم/لا)',
            'applies_to' => 'مطلوب|يجب أن يكون أحد: كل المنشآت، منشآت محددة، أنواع محددة',
        ];
    }

    // public function updatedSearch()
    // {
    //     $this->loadCoupons();
    // }

    // public function updatedSelectedStatu()
    // {
    //     $this->loadCoupons();
    // }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function store()
    {

        if ($this->applies_to === 'specific_establishments') {
            $rules['selectedEstablishments'] = 'required|array|min:1';
        }

        if ($this->applies_to === 'specific_types') {
            $rules['selectedTypes'] = 'required|array|min:1';
        }

        $this->validate();

        $coupon = DiscountCoupon::create([
            'code' => strtoupper($this->code),
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'start_date' => Carbon::parse($this->start_date),
            'end_date' => Carbon::parse($this->end_date),
            'max_uses' => $this->max_uses,
            'current_uses' => 0,
            'is_active' => $this->is_active,
            'applies_to' => $this->applies_to,
            'created_by' => Auth::user()->id,
        ]);

        if ($this->applies_to === 'specific_establishments') {
            $coupon->establishments()->sync($this->selectedEstablishments);
        }

        if ($this->applies_to === 'specific_types') {
            $coupon->establishmentTypes()->sync($this->selectedTypes);
        }

        $this->search = '';
        $this->selectedStatu = null;
        AdminLoggerService::log('اضافة كوبون', 'DiscountCoupon', "إضافة كوبون جديد: {$this->code}");

        $this->resetForm();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم إنشاء الكوبون بنجاح',
        ]);
    }

    public function edit($id)
    {
        $coupon = DiscountCoupon::with(['establishments', 'establishmentTypes'])->findOrFail($id);

        $this->code = $coupon->code;
        $this->description = $coupon->description;
        $this->discount_type = $coupon->discount_type;
        $this->discount_value = $coupon->discount_value;
        $this->start_date = Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i');
        $this->end_date = Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i');
        $this->max_uses = $coupon->max_uses;
        $this->is_active = $coupon->is_active;
        $this->applies_to = $coupon->applies_to;

        $this->selectedEstablishments = $coupon->establishments->pluck('id')->toArray();
        $this->selectedTypes = $coupon->establishmentTypes->pluck('id')->toArray();

        $this->isEdit = true;
        $this->showForm = true;
        $this->couponBeingEdited = $coupon->id;
    }

    public function update()
    {
        $coupon = DiscountCoupon::findOrFail($this->couponBeingEdited);


        if ($this->applies_to === 'specific_establishments') {
            $rules['selectedEstablishments'] = 'required|array|min:1';
        }

        if ($this->applies_to === 'specific_types') {
            $rules['selectedTypes'] = 'required|array|min:1';
        }


        $coupon->update([
            'code' => strtoupper($this->code),
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'start_date' => Carbon::parse($this->start_date),
            'end_date' => Carbon::parse($this->end_date),
            'max_uses' => $this->max_uses,
            'is_active' => $this->is_active,
            'applies_to' => $this->applies_to,
        ]);

        if ($this->applies_to === 'specific_establishments') {
            $coupon->establishments()->sync($this->selectedEstablishments);
        } else {
            $coupon->establishments()->detach();
        }

        if ($this->applies_to === 'specific_types') {
            $coupon->establishmentTypes()->sync($this->selectedTypes);
        } else {
            $coupon->establishmentTypes()->detach();
        }
        AdminLoggerService::log('تعديل كوبون', 'DiscountCoupon', "تعديل كوبون: {$this->code}");

        $this->resetForm();
        // $this->loadCoupons();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم تعديل الكوبون بنجاح',
        ]);
    }

    public function confirmDelete($id)
    {
        $coupon = DiscountCoupon::findOrFail($id);
        $this->deleteTitle = $coupon->code;
        $this->deleteId = $id;
        AdminLoggerService::log('تاكيد حذف كوبون', 'DiscountCoupon', "تاكيد حذف  كوبون: {$this->deleteTitle}");
    }

    public function deleteCoupon()
    {
        DiscountCoupon::destroy($this->deleteId);
        $this->deleteId = null;
        AdminLoggerService::log('حذف كوبون', 'DiscountCoupon', "حذف كوبون: {$this->deleteTitle}");
        // $this->loadCoupons();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'تم حذف الكوبون',
        ]);
    }
    public function toggleVerification($id)
    {

        $coupon = DiscountCoupon::find($id);
        $coupon->update([
            'is_active' => !$coupon->is_active,
        ]);
        $statusText = $coupon->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        AdminLoggerService::log(
            'تعديل تفعيل كوبون',
            'DiscountCoupon',
            "{$statusText} الكوبون: {$coupon->code}"
        );
        // $this->loadCoupons();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $coupon->is_active ? 'تم تفعيل الإعلان بنجاح' : 'تم إلغاء تفعيل الإعلان بنجاح'
        ]);
    }
    public function cancel()
    {
        $this->resetForm();
    }


    public function resetForm()
    {
        $this->code = $this->description = $this->discount_value = $this->max_uses = null;
        $this->discount_type = 'percentage';
        $this->is_active = true;
        $this->start_date = now()->format('Y-m-d\TH:i');
        $this->end_date = now()->addMonth()->format('Y-m-d\TH:i');
        $this->selectedEstablishments = $this->selectedTypes = [];
        $this->applies_to = 'all_establishments';
        $this->isEdit = false;
        $this->showForm = false;
        $this->couponBeingEdited = null;
    }

    public function render()
    {
          $coupons = DiscountCoupon::query()
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->when(is_numeric($this->selectedStatu), fn($q) => $q->where('is_active', $this->selectedStatu))
            ->orderBy('id', 'desc')
            ->paginate(perPage: 10);
        return view('livewire.discount-coupons', compact('coupons'));
    }
}