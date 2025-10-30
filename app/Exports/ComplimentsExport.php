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

        if ($this->request->filled('department_id')) {
            $query->where('department_id', $this->request->department_id);
        }

        if ($this->request->filled('completion_type_id')) {
            $query->where('completion_type_id', $this->request->completion_type_id);
        }

        if ($this->request->filled('status_id')) {
            $query->where('status_id', $this->request->status_id);
        }

        return view('exports.compliments', [
            'compliments' => $query->get(),
        ]);
    }
}
