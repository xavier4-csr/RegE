<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
        .page { padding: 36px 48px; }

        .header { border-bottom: 3px solid #1e40af; padding-bottom: 20px; margin-bottom: 24px; }
        .header-top { overflow: hidden; }
        .logo { float: left; font-size: 28px; font-weight: 700; color: #1e40af; }
        .header-right { float: right; text-align: right; }
        .header-right .title { font-size: 16px; font-weight: 700; color: #0f172a; }
        .header-right .sub { font-size: 10px; color: #64748b; margin-top: 2px; }
        .clearfix::after { content: ''; display: table; clear: both; }

        .company-info { background: #eff6ff; border-left: 4px solid #1e40af; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 6px 6px 0; }
        .company-info .name { font-size: 16px; font-weight: 700; color: #1e40af; }
        .company-info .meta { font-size: 10px; color: #475569; margin-top: 3px; }

        .progress-summary { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px 18px; margin-bottom: 24px; overflow: hidden; }
        .stat { float: left; width: 25%; text-align: center; }
        .stat .num { font-size: 24px; font-weight: 700; color: #1e40af; }
        .stat .lbl { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
        .progress-bar-bg { background: #e2e8f0; height: 8px; border-radius: 4px; margin-top: 12px; clear: both; }
        .progress-bar-fill { height: 8px; border-radius: 4px; }

        .section-title { font-size: 12px; font-weight: 700; color: #0f172a; padding: 8px 12px; border-radius: 4px; margin-bottom: 8px; }
        .section-pending   { background: #fef3c7; color: #92400e; }
        .section-completed { background: #dcfce7; color: #166534; }
        .section-skipped   { background: #f1f5f9; color: #475569; }

        .step-row { border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 6px; overflow: hidden; }
        .step-check { float: left; width: 24px; padding-top: 2px; }
        .step-check .box { width: 16px; height: 16px; border: 2px solid #cbd5e1; border-radius: 3px; display: inline-block; text-align: center; line-height: 12px; font-size: 10px; }
        .step-check .checked { background: #16a34a; border-color: #16a34a; color: white; }
        .step-check .skipped { background: #e2e8f0; border-color: #cbd5e1; color: #94a3b8; }
        .step-body { float: left; width: calc(100% - 24px); padding-left: 8px; }
        .step-title { font-weight: 700; font-size: 11px; color: #0f172a; }
        .step-authority { font-size: 10px; color: #3b82f6; font-weight: 600; margin-top: 1px; }
        .step-desc { font-size: 10px; color: #64748b; margin-top: 3px; line-height: 1.5; }
        .step-meta { font-size: 9px; color: #94a3b8; margin-top: 4px; }
        .step-due-overdue { color: #dc2626; font-weight: 700; }

        .footer { border-top: 1px solid #e2e8f0; margin-top: 24px; padding-top: 14px; font-size: 9px; color: #94a3b8; text-align: center; }
        .notice { background: #fef9c3; border: 1px solid #fde047; border-radius: 4px; padding: 8px 12px; margin-bottom: 16px; font-size: 10px; color: #713f12; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <div class="header-top clearfix">
            <div class="logo">RegE</div>
            <div class="header-right">
                <div class="title">Compliance Checklist</div>
                <div class="sub">Post-Registration Obligations — Kenya</div>
                <div class="sub">Generated: {{ now()->format('d F Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="company-info">
        <div class="name">{{ $company->company_name }}</div>
        <div class="meta">
            {{ ucwords(str_replace('_',' ', $company->business_type)) }}
            @if($company->county) · {{ $company->county }} @endif
            @if($company->registration_number) · Reg: {{ $company->registration_number }} @endif
        </div>
    </div>

    @php
        $total    = $progress->count();
        $done     = $progress->where('status','completed')->count();
        $pending  = $progress->where('status','pending')->count();
        $skipped  = $progress->where('status','skipped')->count();
        $pct      = $total > 0 ? round(($done / $total) * 100) : 0;
        $barColor = $pct >= 100 ? '#16a34a' : ($pct >= 50 ? '#1e40af' : '#f59e0b');
    @endphp

    <div class="progress-summary">
        <div class="clearfix">
            <div class="stat"><div class="num">{{ $total }}</div><div class="lbl">Total Steps</div></div>
            <div class="stat"><div class="num" style="color:#16a34a">{{ $done }}</div><div class="lbl">Completed</div></div>
            <div class="stat"><div class="num" style="color:#f59e0b">{{ $pending }}</div><div class="lbl">Pending</div></div>
            <div class="stat"><div class="num" style="color:#64748b">{{ $skipped }}</div><div class="lbl">Skipped</div></div>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
        </div>
    </div>

    <div class="notice">
        ⚠ This checklist is a guidance document only. Always verify current requirements with the relevant government authority.
        RegE is not a legal or financial advisor.
    </div>

    {{-- Pending steps --}}
    @php $pendingSteps = $progress->where('status','pending'); @endphp
    @if($pendingSteps->count() > 0)
    <div class="section-title section-pending">⏳ Pending — {{ $pendingSteps->count() }} steps</div>
    @foreach($pendingSteps->sortBy('due_date') as $item)
    <div class="step-row">
        <div class="step-check"><div class="box">□</div></div>
        <div class="step-body">
            <div class="step-title">{{ $item->complianceStep->title }}</div>
            <div class="step-authority">{{ $item->complianceStep->authority }}</div>
            <div class="step-desc">{{ $item->complianceStep->description }}</div>
            <div class="step-meta">
                @if($item->due_date)
                    <span class="{{ $item->due_date->isPast() ? 'step-due-overdue' : '' }}">
                        Due: {{ $item->due_date->format('d M Y') }}
                        ({{ $item->due_date->diffForHumans() }})
                    </span>
                @endif
                @if($item->complianceStep->portal_url)
                    · Portal: {{ $item->complianceStep->portal_url }}
                @endif
            </div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Completed steps --}}
    @php $completedSteps = $progress->where('status','completed'); @endphp
    @if($completedSteps->count() > 0)
    <div class="section-title section-completed" style="margin-top:16px">✅ Completed — {{ $completedSteps->count() }} steps</div>
    @foreach($completedSteps as $item)
    <div class="step-row" style="opacity:0.8">
        <div class="step-check"><div class="box checked">✓</div></div>
        <div class="step-body">
            <div class="step-title">{{ $item->complianceStep->title }}</div>
            <div class="step-authority">{{ $item->complianceStep->authority }}</div>
            @if($item->completed_at)
                <div class="step-meta">Completed: {{ $item->completed_at->format('d M Y') }}</div>
            @endif
        </div>
    </div>
    @endforeach
    @endif

    {{-- Skipped steps --}}
    @php $skippedSteps = $progress->where('status','skipped'); @endphp
    @if($skippedSteps->count() > 0)
    <div class="section-title section-skipped" style="margin-top:16px">⏭ Skipped — {{ $skippedSteps->count() }} steps</div>
    @foreach($skippedSteps as $item)
    <div class="step-row" style="opacity:0.6">
        <div class="step-check"><div class="box skipped">—</div></div>
        <div class="step-body">
            <div class="step-title">{{ $item->complianceStep->title }}</div>
            <div class="step-authority">{{ $item->complianceStep->authority }}</div>
        </div>
    </div>
    @endforeach
    @endif

    <div class="footer">
        Generated by RegE — rege.africa | {{ now()->format('d F Y') }} |
        This document is for reference purposes only and does not constitute legal advice.
    </div>

</div>
</body>
</html>