<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $companies = Auth::user()->companies()->get();
        $documents = Auth::user()->documents()->with('company')->latest()->get();
        return view('documents.index', compact('companies', 'documents'));
    }

    public function generateRegistrationCert(Company $company)
    {
        $this->authorizeOwner($company);

        $pdf = Pdf::loadView('documents.pdf.registration-cert', compact('company'))
                  ->setPaper('a4', 'portrait');

        $filename = 'registration-cert-' . $company->id . '-' . now()->format('YmdHis') . '.pdf';
        $path     = 'documents/' . Auth::id() . '/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'user_id'      => Auth::id(),
            'company_id'   => $company->id,
            'title'        => 'Registration Summary — ' . $company->company_name,
            'type'         => 'registration_cert',
            'file_path'    => $path,
            'file_name'    => $filename,
            'file_size'    => strlen($pdf->output()),
            'is_generated' => true,
        ]);

        return $pdf->download($filename);
    }

    public function generateComplianceChecklist(Company $company)
    {
        $this->authorizeOwner($company);

        $progress = $company->complianceProgress()
            ->with('complianceStep')
            ->orderBy('due_date')
            ->get();

        $pdf = Pdf::loadView('documents.pdf.compliance-checklist', compact('company', 'progress'))
                  ->setPaper('a4', 'portrait');

        $filename = 'compliance-checklist-' . $company->id . '-' . now()->format('YmdHis') . '.pdf';
        $path     = 'documents/' . Auth::id() . '/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'user_id'      => Auth::id(),
            'company_id'   => $company->id,
            'title'        => 'Compliance Checklist — ' . $company->company_name,
            'type'         => 'registration_cert',
            'file_path'    => $path,
            'file_name'    => $filename,
            'file_size'    => strlen($pdf->output()),
            'is_generated' => true,
        ]);

        return $pdf->download($filename);
    }

    public function generatePartnershipDeed(Request $request, Company $company)
    {
        $this->authorizeOwner($company);

        $data = $request->validate([
            'partners'           => ['required', 'array', 'min:2'],
            'partners.*.name'    => ['required', 'string'],
            'partners.*.id_no'   => ['required', 'string'],
            'partners.*.share'   => ['required', 'numeric', 'min:1', 'max:100'],
            'business_address'   => ['required', 'string'],
            'commencement_date'  => ['required', 'date'],
        ]);

        $pdf = Pdf::loadView('documents.pdf.partnership-deed', [
            'company'          => $company,
            'partners'         => $data['partners'],
            'business_address' => $data['business_address'],
            'commencement_date'=> $data['commencement_date'],
            'generated_at'     => now(),
        ])->setPaper('a4', 'portrait');

        $filename = 'partnership-deed-' . $company->id . '-' . now()->format('YmdHis') . '.pdf';
        $path     = 'documents/' . Auth::id() . '/' . $filename;

        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'user_id'      => Auth::id(),
            'company_id'   => $company->id,
            'title'        => 'Partnership Deed — ' . $company->company_name,
            'type'         => 'partnership_deed',
            'file_path'    => $path,
            'file_name'    => $filename,
            'file_size'    => strlen($pdf->output()),
            'is_generated' => true,
        ]);

        return $pdf->download($filename);
    }

    public function download(Document $document)
    {
        $this->authorizeDocOwner($document);
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function destroy(Document $document)
    {
        $this->authorizeDocOwner($document);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Document deleted.');
    }

    private function authorizeOwner(Company $company): void
    {
        if ($company->user_id !== Auth::id()) abort(403);
    }

    private function authorizeDocOwner(Document $document): void
    {
        if ($document->user_id !== Auth::id()) abort(403);
    }
}