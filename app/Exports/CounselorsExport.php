<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CounselorsExport implements FromCollection, WithHeadings
{
    /**
     * Returns the data collection to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Fetch the data from the database (you can customize this query)
        return User::select('first_name', 'last_name', 'email', 'user_status')
            ->where('user_type', '2')
            ->whereNull('deleted_at')
            ->get()
            ->map(function($admin) {
                // Transform user_status to Active/Inactive
                $admin->user_status = $admin->user_status == 1 ? 'Active' : 'Inactive';
                return $admin;
            });
    }

    /**
     * Define the headers for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Status',
        ];
    }
}
