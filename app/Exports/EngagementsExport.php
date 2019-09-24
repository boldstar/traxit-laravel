<?php

namespace App\Exports;

use App\Models\Tenant\Engagement;
use App\Models\Tenant\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngagementsExport implements FromQuery, WithHeadings
{
     /**
     * query by year
     */
    public function __construct($ids) {
        $this->ids = $ids;
    }

    /**
    * @return \Illuminate\Support\Query
    */
    public function query()
    {
        return Engagement::query()->whereIn('id', $this->ids)
                                ->select(
                                    'id', 
                                    'category', 
                                    'name', 
                                    'type', 
                                    'description', 
                                    'return_type',
                                    'title', 
                                    'year', 
                                    'assigned_to', 
                                    'status', 
                                    'estimated_date', 
                                    'created_at',
                                    'updated_at'
                                );

    }

    public function headings(): array
    {
        return [
            'Id',
            'Category',
            'Name',
            'Engagement Type',
            'Workflow',
            'Return Type',
            'Time Period',
            'Tax Year',
            'Assigned To',
            'Status',
            'Due Date',
            'Created Date',
            'Last Updated'
        ];
    }
}
