<?php

namespace App\Observers;

use Auth;
use App\Models\Tenant\EngagementActions;

class EngagementObserver
{
    public function saved($model)
    { 
        if ($model->wasRecentlyCreated == true) {
            // Data was just created
            $action = 'created';
        }  else if($model->done == false) {
            $action = 'updated';
        } else if($model->done == true) {
            $action = 'completed';
        }
        
        $engagements = EngagementActions::where('engagement_id', $model->id)->get();
        $engagementActionExist = $engagements->contains('action', 'completed');

        if($engagementActionExist == true) {
            foreach ($engagements as $engagement) {
                $engagement->where(['action' => 'completed', 'engagement_id' => $model->id])->delete();
            }
        }

        if (Auth::check()) {
            EngagementActions::create([
                'user_id' => Auth::user()->id,
                'engagement_model' => $model->getTable(),
                'engagement_id' => $model->id,
                'workflow_id' => $model->workflow_id,
                'action'  => $action,
                'category' => $model->category,
                'title' => $model->title,
                'type' => $model->type,
                'name' => $model->name,
                'year' => $model->year,
                'return_type' => $model->return_type,
                'status' => $model->status
            ]);
        }
    }

    public function deleting($model)
    {
        if (Auth::check()) {
            EngagementActions::create([
                'user_id' => Auth::user()->id,
                'engagement_model' => $model->getTable(),
                'engagement_id' => $model->id,
                'workflow_id' => $model->workflow_id,
                'action' => 'deleted',
                'category' => $model->category,
                'title' => $model->title,
                'type' => $model->type,
                'name' => $model->name,
                'year' => $model->year,
                'return_type' => $model->return_type,
                'status' => $model->status
            ]);
        }
    }
}
