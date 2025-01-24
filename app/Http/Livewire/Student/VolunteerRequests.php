<?php

namespace App\Http\Livewire\Student;

use Livewire\Component;

class VolunteerRequests extends Component
{
    public $tab = 'list';
    public $volunteers;
    public $submittedRequests;

    public function mount($volunteers, $submittedRequests)
    {
        $this->volunteers = $volunteers;
        $this->submittedRequests = $submittedRequests;
    }

    protected function calculateRemainingNeeded($volunteer)
    {
        // Use the model's method to calculate remaining volunteers
        return $volunteer->remainingVolunteersNeeded();
    }

    public function render()
    {
        $availableVolunteers = $this->volunteers->filter(function ($volunteer) {
            return !$this->submittedRequests->pluck('volunteer_id')->contains($volunteer->volunteer_id);
        });

        $mappedVolunteers = $availableVolunteers->map(function ($volunteer) {
            $volunteer->remaining_needed = $this->calculateRemainingNeeded($volunteer);
            return $volunteer;
        });

        return view('livewire.student.volunteer-requests', [
            'volunteers' => $mappedVolunteers,
            'submittedRequests' => $this->submittedRequests,
        ]);
    }
}
