<?php

namespace App\Livewire;

use App\Models\Establishment;
use App\Models\EstablishmentType;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\FirebaseService;
use App\Services\AdminLoggerService;


class Establishments extends Component
{
   use WithPagination;

    private $firebaseService;
    
    public function __construct() {
        $this->firebaseService = new FirebaseService();
    }

   public $search = '';
    public $selectedType = '';
    public $selectedStatus = '';

    protected $paginationTheme = 'bootstrap';

        protected $queryString = ['search', 'selectedType', 'selectedStatus'];

    public function updating($field)
    {
        $this->resetPage();
    }

    public function toggleVerification($id)
    {
        $establishment = Establishment::find($id);
        if ($establishment) {
            $establishment->is_verified = !$establishment->is_verified;
            $establishment->save();
            
           $statusText = $establishment->is_verified ? 'توثيق' : 'إلغاء توثيق';
            AdminLoggerService::log(
            'تعديل التوثيق',
            'Establishment',
            "{$statusText} المنشأة: {$establishment->name}"
            );
        if ($establishment->owner){
            $owner = $establishment->owner;
            $title = '';
            $body = '';
            $notificationType = '';
            if ($establishment->is_verified) {
                $title = "تهانينا! تم توثيق منشأتك";
                $body = "لقد تم توثيق منشأتك '{$establishment->name}' بنجاح في منصتنا.";
                $notificationType = 'establishment_verified';
            } else {
                $title = "إشعار بإلغاء توثيق المنشأة";
                $body = "نأسف لإبلاغك، تم إلغاء توثيق منشأتك '{$establishment->name}'. لمزيد من التفاصيل، يرجى مراجعة الدعم الفني.";
                $notificationType = 'establishment_unverified';
            }
            $data = [
                'type' => $notificationType,
                'establishment_id' => (string)$establishment->id,
                'user_id' => (string)$owner->id,
            ];
            if ($owner->device_token){
                try {
                        $this->firebaseService->sendNotification($owner->device_token, $title, $body, $data);
                    } catch (\Exception $e) {
                        \Log::error("Failed to send verification notification to user: {$owner->id}", ['error' => $e->getMessage()]);
                    }
            }
        }

        }

    }

    public function render()
    {
        $establishments = Establishment::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->selectedType, fn($q) => $q->where('type_id', $this->selectedType))
            ->when($this->selectedStatus !== '', fn($q) => $q->where('is_verified', $this->selectedStatus))
            ->with(['type', 'region'])
            ->latest()
            ->paginate(10);

        $types = EstablishmentType::all();
        $statuses = [
            1 => 'موثقة',
            0 => 'غير موثقة',
        ];

        return view('livewire.establishments', compact('establishments', 'types', 'statuses'));
    }
}