<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $participants;

    public function __construct($participants)
    {
        $this->participants = $participants;
    }

    public function collection()
    {
        return $this->participants;
    }

    public function headings(): array
    {
        return [
            'No',
            'Student Name',
            'Matric No',
            'Email',
            'Phone No',
            'Course',
            'Register Date',
            'Status',
            'Attendance Date'
        ];
    }

    public function map($participant): array
    {
        static $index = 0;
        $index++;
        
        return [
            $index,
            $participant->stud_name,
            $participant->matric_no,
            $participant->email,
            $participant->stud_phoneNo,
            $participant->stud_course,
            $participant->register_datetime,
            $participant->status,
            $participant->attendance_datetime ?? 'N/A'
        ];
    }
} 