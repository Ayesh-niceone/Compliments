<?php

namespace App\Http\Controllers;

use App\Models\Compliment;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function dashboardDonuts()
    {
        // 1) Department
        $departments = Compliment::selectRaw('department_id, COUNT(*) as total')
            ->groupBy('department_id')
            ->with('department')
            ->get()
            ->map(fn($row) => [
                'label' => $row->department->name ?? 'Unknown',
                'value' => $row->total
            ]);

        // 2) Completion Type
        $completionType = Compliment::selectRaw('completion_type_id, COUNT(*) as total')
            ->groupBy('completion_type_id')
            ->with('completion_type')
            ->get()
            ->map(fn($row) => [
                'label' => $row->completion_type->name ?? 'Unknown',
                'value' => $row->total
            ]);

        // 3) Target Type
        $targetType = Compliment::selectRaw('target_type, COUNT(*) as total')
            ->groupBy('target_type')
            ->get()
            ->map(fn($row) => [
                'label' => ucfirst($row->target_type ?? 'N/A'),
                'value' => $row->total
            ]);

        // 4) Care User (CSR Responsible)
        $careUsers = Compliment::selectRaw('care_user_id, COUNT(*) as total')
            ->groupBy('care_user_id')
            ->with('careUser')
            ->get()
            ->map(fn($row) => [
                'label' => $row->careUser->name ?? 'Unassigned',
                'value' => $row->total
            ]);

        return response()->json([
            'department_chart'    => $departments,
            'completion_chart'    => $completionType,
            'target_type_chart'   => $targetType,
            'care_user_chart'     => $careUsers,
        ]);
    }
}
