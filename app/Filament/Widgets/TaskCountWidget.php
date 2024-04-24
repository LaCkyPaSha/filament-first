<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use DateTime;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TaskCountWidget extends BaseWidget
{

    protected int | string | array $columnSpan = '2';
    protected function getStats(): array
    {

        $userId = Auth::user()->id;

        $tasksQuery = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })
            ->whereIn('status_id', ['1', '2', '3']);

        $tasksCount = $tasksQuery->count();

        $lastTaskDeadline = $tasksQuery->orderBy('deadline')->pluck('deadline');

        $lastTaskId = $tasksQuery->orderBy('deadline')->pluck('id');

        $format = 'Y-m-d H:i:s';

        $id = $lastTaskId->first();

        $dateTime = DateTime::createFromFormat($format, $lastTaskDeadline->first());
        $timeNow = now()->toDateTime();

        $if = $timeNow<$dateTime;

        $hasNoTasks = $tasksCount===0;

        $label=null;
        if($hasNoTasks){
            $label = 'You dont have tasks';
        }else {
            $label = $if ? 'Next task is until' : 'You have uncompleted task from';
        }
        return [
            Stat::make('Tasks you have', $tasksCount)
                ->description('What are you up to? :)')
                ->color('success')
                ->url('/admin/tasks')
                ->icon('heroicon-o-check-circle')
                ->chart([2, 8, 5, 12, 7, 10, 15, 10, 7, 12, 5, 8, 2]),
            Stat::make($label, $lastTaskDeadline->first())
                ->description($hasNoTasks ?'Ahh chill =)' : 'Lets do that')
                ->color($hasNoTasks ? 'success' : ($if ? 'warning' : 'danger'))
                ->url($hasNoTasks ? "/news" : "/admin/tasks/{$id}/edit")
                ->icon('heroicon-o-check-circle')
                ->chart([15, 10, 7, 12, 5, 8, 2, 8, 5, 12, 7, 10, 15])
        ];
    }
}
