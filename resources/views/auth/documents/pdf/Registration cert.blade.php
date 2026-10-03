<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

        .page { padding: 40px 50px; }

        .header { text-align: center; border-bottom: 3px solid #1e40af; padding-bottom: 24px; margin-bottom: 32px; }
        .logo-text { font-size: 32px; font-weight: 700; color: #1e40af; letter-spacing: 2px; }
        .header-sub { font-size: 11px; color: #64748b; margin-top: 4px; letter-spacing: 1px; text-transform: uppercase; }
        .cert-title { font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 16px; }
        .cert-number { font-size: 11px; color: #94a3b8; margin-top: 4px; font-family: monospace; }

        .cert-body { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 28px 32px; margin-bottom: 28px; }
        .cert-intro { font-size: 13px; color: #475569; line-height: 1.8; margin-bottom: 20px; text-align: center; }
        .company-name { font-size: 22px; font-weight: 700; color: #1e40af; text-align: center; margin: 16px 0; }

        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px 12px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        td:first-child { color: #64748b; width: 40%; font-weight: 600; }
        td:last-child { color: #0f172a; font-weight: 500; }

        .status-badge { display: inline-block; background: #dcfce7; color: #166534; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }

        .compliance-section { margin-top: 24px; }
        .compliance-title { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0; }
        .progress-bar-bg { background: #e2e8f0; height: 10px; border-radius: 5px; }
        .progress-bar-fill { background: #1e40af; height: 10px; border-radius: 5px; }
        .progress-text { font-size: 11px; color: #64748b; margin-top: 4px; }

        .footer { border-top: 2px solid #e2e8f0; padding-top: 20px; margin-top: 32px; }
        .footer-grid { width: 100%; }
        .footer-left { float: left; width: 60%; }
        .footer-right { float: right; width: 35%; text-align: right; }
        .footer-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .footer-value { font-size: 11px; color: #475569; margin-top: 3px; }
        .signature-line { border-bottom: 1px solid #cbd5e1; width: 160px; margin-top: 30px; margin-bottom: 4px; }
        .watermark { font-size: 9px; color: #cbd5e1; text-align: center; margin-top: 16px; }

        .clearfix::after { content: ''; display: table; clear: both; }
        .seal { float: right; width: 80px; height: 80px; border: 3px solid #1e40af; border-radius: 50%; text-align: center; padding-top: 18px; }
        .seal-text { font-size: 9px; color: #1e40af; font-weight: 700; text-transform: uppercase; line-height: 1.4; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="logo-text">RegE</div>
        <div class="header-sub">Business Registration & Compliance Platform — Kenya</div>
        <div class="cert-title">Business Registration Summary</div>
        <div class="cert-number">REGE/CERT/{{ strtoupper(substr(md5($company->id . $company->company_name), 0, 12)) }}</div>
    </div>

    {{-- Certificate body --}}
    <div class="cert-body">
        <div class="cert-intro">
            This document confirms that the business detailed below has been registered on the RegE platform
            and has completed the onboarding process. This is a summary document and does not replace
            official registration certificates issued by the Business Registration Service (BRS), Kenya.
        </div>

        <div class="company-name">{{ strtoupper($company->company_name) }}</div>

        <table>
            <tr>
                <td>Business Name</td>
                <td>{{ $company->company_name }}</td>
            </tr>
            <tr>
                <td>Business Type</td>
                <td>{{ ucwords(str_replace('_', ' ', $company->business_type)) }}</td>
            </tr>
            <tr>
                <td>Owner / Director</td>
                <td>{{ $company->owner_name }}</td>
            </tr>
            <tr>
                <td>Industry</td>
                <td>{{ $company->category?->name ?? 'Not specified' }}</td>
            </tr>
            <tr>
                <td>Business Address</td>
                <td>{{ collect([$company->street_address, $company->city, $company->county])->filter()->join(', ') }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{ $company->phone ?? 'Not provided' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $company->email ?? 'Not provided' }}</td>
            </tr>
            @if($company->registration_number)
            <tr>
                <td>BRS Registration No.</td>
                <td>{{ $company->registration_number }}</td>
            </tr>
            @endif
            @if($company->registration_date)
            <tr>
                <td>Registration Date</td>
                <td>{{ $company->registration_date->format('d F Y') }}</td>
            </tr>
            @endif
            <tr>
                <td>Platform Status</td>
                <td><span class="status-badge">{{ strtoupper($company->status) }}</span></td>
            </tr>
            <tr>
                <td>Listed on RegE</td>
                <td>{{ $company->created_at->format('d F Y') }}</td>
            </tr>
        </table>

        {{-- Compliance progress --}}
        <div class="compliance-section">
            <div class="compliance-title">Compliance Progress</div>
            @php
                $total = $company->complianceProgress()->count();
                $done  = $company->complianceProgress()->where('status','completed')->count();
                $pct   = $total > 0 ? round(($done / $total) * 100) : 0;
            @endphp
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $pct }}%"></div>
            </div>
            <div class="progress-text">{{ $done }} of {{ $total }} compliance steps completed ({{ $pct }}%)</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-grid clearfix">
            <div class="footer-left">
                <div class="footer-label">Generated by</div>
                <div class="footer-value">RegE — Business Registration & Compliance Platform</div>
                <div class="footer-value">rege.africa | support@rege.africa</div>

                <div style="margin-top: 20px;">
                    <div class="signature-line"></div>
                    <div class="footer-label">Authorised by RegE Platform</div>
                </div>
            </div>
            <div class="footer-right">
                <div class="seal">
                    <div class="seal-text">RegE<br>Verified<br>Business</div>
                </div>
                <div style="margin-top: 12px; clear: both;">
                    <div class="footer-label">Generated on</div>
                    <div class="footer-value">{{ now()->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>
        <div class="watermark">
            This document was automatically generated by the RegE platform. It is for reference purposes only.
            For official business registration, visit ecitizen.go.ke | BRS Reference: {{ $company->registration_number ?? 'Pending' }}
        </div>
    </div>

</div>
</body>
</html>