<?php

namespace App\Http\Controllers;

use App\Models\Ecertificate;
use App\Models\Event;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Correct facade import
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EcertController extends Controller
{
    public function generateEcert($eventId)
    {
        try {
            $student = Auth::user()->student;
            $event = Event::findOrFail($eventId);

            // Get template path - either custom or default
            $templatePath = $this->getTemplatePathForEvent($event);

            $uniqueCode = strtoupper(Str::random(10));
            $data = [
                'student_name' => $student->stud_name,
                'matric_no' => $student->user->matric_no,
                'event_name' => $event->event_name,
                'event_date' => $this->getEventDate($event),
                'unique_code' => $uniqueCode,
                'event' => $event,
                'template_path' => $templatePath, // Add template path to data
            ];

            // Only use custom orientation if template is approved, otherwise use portrait
            $pdfOrientation = ($event->cert_template && $event->template_status === 'approved') ? 
                ($event->cert_orientation === 'landscape' ? 'landscape' : 'portrait') : 
                'portrait';

            $pdf = PDF::loadView('student.events.eCertification.cert', $data)
                    ->setPaper('a4', $pdfOrientation)
                    ->setOptions([
                        'margin-top' => 0,
                        'margin-right' => 0,
                        'margin-bottom' => 0,
                        'margin-left' => 0,
                        'defaultFont' => 'Montserrat',
                        'fontDir' => public_path('fonts/'),
                        'fontCache' => storage_path('fonts/'),
                        'isRemoteEnabled' => true,
                    ]);

            $filePath = 'certificates/' . $uniqueCode . '.pdf';
            Storage::put($filePath, $pdf->output());

            Ecertificate::create([
                'stud_id' => $student->stud_id,
                'event_id' => $event->event_id,
                'ecert_file' => $filePath,
                'unique_code' => $uniqueCode,
                'ecert_datetime' => now(),
            ]);

            return redirect()->back()->with('success', 'E-Certificate generated successfully!');
        } catch (\Exception $e) {
            Log::error('Certificate generation error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An error occurred while generating the certificate.');
        }
    }

    // Add this new private method to handle template selection
    private function getTemplatePathForEvent($event)
    {
        if ($event->cert_template && $event->template_status === 'approved') {
            return storage_path('app/public/' . $event->cert_template);
        }
        
        // Use default template if no custom template or if template was rejected
        return public_path('images/default-certificate-portrait.png');
    }

    // Function to handle date formatting based on single-day or multi-day event
    private function getEventDate($event)
    {
        // Single-day event handling
        if ($event->event_date) {
            return Carbon::parse($event->event_date)->format('d/m/Y');
        }

        // Multi-day event handling
        $startDate = $event->event_start_date ? Carbon::parse($event->event_start_date)->format('d/m/Y') : null;
        $endDate = $event->event_end_date ? Carbon::parse($event->event_end_date)->format('d/m/Y') : null;

        // If both start and end dates are available, return the range
        if ($startDate && $endDate) {
            return $startDate . ' - ' . $endDate;
        }

        // Handle cases where only one of the dates is available
        if ($startDate) {
            return $startDate;
        }

        if ($endDate) {
            return $endDate;
        }

        // If no dates are available, return a default message
        return 'Date not available';
    }

    public function downloadEcert($ecertId)
    {
        // Fetch the certificate details
        $ecert = Ecertificate::findOrFail($ecertId);

        // Fetch the event details
        $event = Event::findOrFail($ecert->event_id);

        // Generate the custom filename using event name and unique code
        $fileName = $event->event_name . '_' . $ecert->unique_code . '.pdf';

        // Download the certificate and rename the file
        return response()->download(storage_path('app/' . $ecert->ecert_file), $fileName);
    }
}
