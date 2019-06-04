<?php

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
        $workflow->workflow = 'Personal Returns';
        $workflow->save();
        foreach($statuses as $status) {
            $workflow->statuses()->create([
                'status' => $status,
                'order' => array_search($status, $statuses)
            ]);
        };

        $workflow = new Workflow();
        $workflow->workflow = 'Business Returns';
        $workflow->save();
        foreach($statuses as $status) {
            $workflow->statuses()->create([
                'status' => $status,
                'order' => array_search($status, $statuses)
            ]);
        };
        
        $workflow = new Workflow();
        $workflow->workflow = 'Bookkeeping';
        $workflow->save();
        foreach($statuses as $status) {
            $workflow->statuses()->create([
                'status' => $status,
                'order' => array_search($status, $statuses)
            ]);
        };

    }
}
