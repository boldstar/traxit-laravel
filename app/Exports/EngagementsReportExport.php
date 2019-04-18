<?php

namespace App\Exports;

use App\Models\Tenant\Engagement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EngagementsReportExport implements FromQuery

{
    /**
     * query by year
     */
    public function __construct($year, $type, $from, $to, $action, $workflow_id, $status, $return_type) {
        $this->year = $year;
        $this->type = $type;
        $this->from = $from;
        $this->to = $to;
        $this->workflow_id = $workflow_id;
        $this->status = $status;
        $this->action = $action;
        $this->return_type = $return_type;
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function query()
    {
        $conditions = [];

        if(isset($this->from) && $this->from){
            array_push($conditions, ['created_at', '>=', $this->from]);
        }
    
        if(isset($this->to) && $this->to){
            array_push($conditions[], ['created_at', '<=', $this->to]);
        }

        if(isset($this->type) && $this->type){
            array_push($conditions, ['type', $this->type]);
        }
    
        if(isset($this->year) && $this->year){
            array_push($conditions, ['year', $this->year]);
        }

        // if(isset($this->action) && $this->action){
        //     $conditions[]=['action', $this->action];
        // }

        if(isset($this->workflow_id) && $this->workflow_id){
            array_push($conditions, ['workflow_id', $this->workflow_id]);
        }

        if(isset($this->status) && $this->status){
            array_push($conditions, ['status', $this->status]);
        }

        if(isset($this->return_type) && $this->return_type){
            array_push($conditions, ['return_type', $this->return_type]);
        }

        return Engagement::query()->where($conditions);
    }

    // public function headings(): array
    // {
    //     return [
    //         'Id',
    //     ];
    // }
}
