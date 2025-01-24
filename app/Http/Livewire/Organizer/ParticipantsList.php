<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use App\Exports\ParticipantsExport;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantsList extends Component
{
    public $event;
    public $search = '';
    public $statusFilter = '';
    
    protected $queryString = ['search', 'statusFilter'];

    public function mount($event)
    {
        $this->event = $event;
    }

    public function getParticipantsProperty()
    {
        return $this->event->attendances()
            ->join('students', 'attendance.stud_id', '=', 'students.stud_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.role_name', 'student')
            ->select(
                'students.stud_name',
                'users.matric_no',
                'users.email',
                'students.stud_phoneNo',
                'students.stud_course',
                'attendance.register_datetime',
                'attendance.status',
                'attendance.attendance_datetime'
            )
            ->when($this->search !== '', function($query) {
                $query->where(function($q) {
                    $q->where('students.stud_name', 'like', '%' . $this->search . '%')
                      ->orWhere('users.matric_no', 'like', '%' . $this->search . '%')
                      ->orWhere('users.email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function($query) {
                $query->where('attendance.status', $this->statusFilter);
            })
            ->orderBy('students.stud_name')
            ->get();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter']);
    }

    public function exportToExcel()
    {
        return Excel::download(
            new ParticipantsExport($this->participants), 
            'participants-' . $this->event->event_name . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.organizer.participants-list', [
            'participants' => $this->participants
        ]);
    }
} 