<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;


class IntermittentFastingTimer extends Component
{
    public string $defaultStartTime;
    public string $defaultEndTime;
    // public array $timeMarkers;
    // public string $fastingDuration;
    // public string $eatingDuration;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $defaultStartTime = '12:00',
        string $defaultEndTime = '20:00'
    ) {
        $this->defaultStartTime = $defaultStartTime;
        $this->defaultEndTime = $defaultEndTime;
        // $this->timeMarkers = $this->generateTimeMarkers(); // not needed for this project
        // $this->fastingDuration = $this->calculateFastingDuration();
        // $this->eatingDuration = $this->calculateEatingDuration();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.intermittent-fasting-timer', [
            'startTime' => $this->defaultStartTime,
            'endTime' => $this->defaultEndTime,
            // 'timeMarkers' => $this->timeMarkers, // not needed for this project
            // 'fastingDuration' => $this->fastingDuration,
            // 'eatingDuration' => $this->eatingDuration,
        ]);
    }

    /**
     * Generate time markers for the timer circle.
     */
    private function generateTimeMarkers(): array
    {
        $markers = [];
        $radius = 45;
        for ($i = 0; $i < 24; $i += 6) {
            $angle = ($i / 24) * 360 - 90;
            $x = 50 + $radius * cos($angle * pi() / 180);
            $y = 53 + $radius * sin($angle * pi() / 180);
            $markers[] = [
                'hour' => $i === 0 ? '0/24' : $i,
                'x' => $x,
                'y' => $y,
            ];
        }
        return $markers;
    }

    /**
     * Calculate the fasting duration.
     */
    private function calculateFastingDuration(): string
    {
        $start = Carbon::createFromFormat('H:i', $this->defaultStartTime);
        $end = Carbon::createFromFormat('H:i', $this->defaultEndTime);
        
        if ($end < $start) {
            $end->addDay();
        }

        $fastingDuration = $start->diffInHours($end);
        return "{$fastingDuration}h 0m";
    }

    /**
     * Calculate the eating duration.
     */
    // private function calculateEatingDuration(): string
    // {
    //     $fastingHours = (int) explode('h', $this->fastingDuration)[0];
    //     $eatingHours = 24 - $fastingHours;
    //     return "{$eatingHours}h 0m";
    // }
}
