<?php

namespace database\seeds\tenant;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Workflow;
use App\Models\Tenant\Status;

class WorkflowTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Received', 'Data Entry', 'Review', 'Complete'];

        $workflow = new Workflow();
        $workflow->workflow = 'Individual Returns';
        $workflow->engagement_type = 'Tax Return';
        $workflow->save();
        foreach($statuses as $status) {
            $workflow->statuses()->create([
                'status' => $status,
                'order' => array_search($status, $statuses)
            ]);
        };

        $workflow = new Workflow();
        $workflow->workflow = 'Business Returns';
        $workflow->engagement_type = 'Tax Return';
        $workflow->save();
        foreach($statuses as $status) {
            $workflow->statuses()->create([
                'status' => $status,
                'order' => array_search($status, $statuses)
            ]);
        };
        
        $workflow = new Workflow();
        $workflow->workflow = 'Bookkeeping';
        $workflow->engagement_type = 'Bookkeeping';
        $workflow->save();
        foreach($statuses as $status) {
            $workflow->statuses()->create([
                'status' => $status,
                'order' => array_search($status, $statuses)
            ]);
        };

    }
}
