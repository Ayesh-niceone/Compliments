<?php

namespace App\Exports;

use App\Models\Compliment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ComplimentsExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Compliment::with(['department', 'careUser', 'completion_type', 'status']);

        // ✅ Apply filters if provided
        if ($this->request->filled('department_id')) {
            $query->where('department_id', $this->request->department_id);
        }
        if ($this->request->filled('completion_type_id')) {
            $query->where('completion_type_id', $this->request->completion_type_id);
        }
        if ($this->request->filled('status_id')) {
            $query->where('status_id', $this->request->status_id);
        }
        if ($this->request->filled('care_user_id')) {
            $query->where('care_user_id', $this->request->care_user_id);
        }
        if ($this->request->filled('target_type')) {
            $query->where('target_type', $this->request->target_type);
        }
        if ($this->request->filled('date_from') && $this->request->filled('date_to')) {
            $query->whereBetween('created_at', [$this->request->date_from, $this->request->date_to]);
        }
        return view('exports.compliments', [
            'compliments' => $query->get(),
        ]);
    }
}
