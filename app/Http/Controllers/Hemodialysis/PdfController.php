<?php

namespace App\Http\Controllers\Hemodialysis;

use App\Http\Controllers\Controller;
use App\Models\Hemodialysis\HemodialysisAdmission;
use App\Models\Hemodialysis\HemodialysisLaboratoryMonitor;
use App\Models\Hemodialysis\HemodialysisMedicalEvaluation;
use App\Models\Hemodialysis\HemodialysisNursingNote;
use App\Models\Hemodialysis\HemodialysisSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class PdfController extends Controller
{
    public function admission(HemodialysisAdmission $record): Response|View
    {
        return $this->download('pdf.hemodialysis.admission', compact('record'), 'historia-ingreso-hd-'.$record->id.'.pdf');
    }

    public function evaluation(HemodialysisMedicalEvaluation $record): Response|View
    {
        return $this->download('pdf.hemodialysis.medical-evaluation', compact('record'), 'evaluacion-medica-hd-'.$record->id.'.pdf');
    }

    public function session(HemodialysisSession $record): Response|View
    {
        return $this->download('pdf.hemodialysis.session', compact('record'), 'ficha-hemodialisis-'.$record->id.'.pdf');
    }

    public function nursingNote(HemodialysisNursingNote $record): Response|View
    {
        return $this->download('pdf.hemodialysis.nursing-note', compact('record'), 'nota-enfermeria-hd-'.$record->id.'.pdf');
    }

    public function laboratoryMonitor(HemodialysisLaboratoryMonitor $record): Response|View
    {
        $record->load('results');
        return $this->download('pdf.hemodialysis.laboratory-monitor', compact('record'), 'monitoreo-laboratorio-hd-'.$record->id.'.pdf');
    }

    private function download(string $view, array $data, string $filename): Response|View
    {
        if (! app()->bound('dompdf.wrapper')) {
            return view($view, $data);
        }

        return app('dompdf.wrapper')->loadView($view, $data)->download($filename);
    }
}
