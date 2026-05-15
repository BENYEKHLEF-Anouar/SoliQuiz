<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport de Résultats - SoliQuiz</title>
    <style>
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ public_path('fonts/PlusJakartaSans-Black.ttf') }}') format('truetype');
            font-weight: 900;
            font-style: normal;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            font-weight: 900;
            font-size: 10px;
            line-height: 15px;
            color: #1e293b; 
            margin: 0; 
            padding: 0;
            background: #fff;
        }

        .page { padding: 48px 52px; }

        /* ── Header ───────────────────────────────────────── */
        .header {
            margin-bottom: 40px;
            padding-bottom: 28px;
            border-bottom: 1.5px solid #e2e8f0;
            position: relative;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .header-left { display: flex; align-items: center; gap: 14px; }

        .logo-box {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border-radius: 14px;
            overflow: hidden;
            /* box-shadow: 0 2px 10px rgba(22, 155, 182, 0.18); */
        }
        .logo-img { width: 100%; height: 100%; display: block; }

        .brand-name {
            font-size: 28px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: -1.5px;
            /* font-style: italic; */
            line-height: 1;
        }
        .brand-name span { color: #169BB6; }

        .brand-tagline {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-top: 5px;
            font-style: normal;
        }

        .doc-meta { text-align: right; padding-top: 4px; }
        .doc-label {
            font-size: 7.5px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            margin-bottom: 5px;
        }
        .doc-id {
            font-size: 12px;
            color: #169BB6;
            letter-spacing: 1px;
        }
        .doc-date {
            font-size: 8.5px;
            color: #64748b;
            margin-top: 4px;
        }

        /* ── Summary Card ──────────────────────────────────── */
        .summary-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 60%, #ecfdf5 100%);
            border-radius: 22px;
            padding: 28px 32px;
            border: 1.5px solid #e0f2fe;
            margin-bottom: 36px;
            position: relative;
            overflow: hidden;
        }

        /* subtle decorative accent in the corner */
        .summary-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(22, 155, 182, 0.07);
        }
        .summary-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: 40%;
            width: 160px;
            height: 80px;
            border-radius: 50%;
            background: rgba(5, 150, 105, 0.04);
        }

        .summary-grid { width: 100%; border-collapse: collapse; position: relative; z-index: 1; }
        .summary-grid td {
            padding: 10px 16px 10px 0;
            vertical-align: top;
        }
        .summary-grid tr:first-child td { padding-bottom: 14px; }
        .summary-grid tr:last-child td { padding-top: 14px; border-top: 1px solid #e2e8f0; }

        .stat-label {
            font-size: 7.5px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 6px;
        }
        .stat-value { font-size: 13px; color: #0f172a; line-height: 1.2; }
        .stat-highlight { color: #169BB6; }

        .hash-value {
            font-size: 8.5px;
            color: #94a3b8;
            letter-spacing: 0.5px;
            word-break: break-all;
        }

        /* ── Section Title ─────────────────────────────────── */
        .section-title {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            padding-left: 4px;
        }

        /* ── Results Table ─────────────────────────────────── */
        .table-wrapper {
            border-radius: 18px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
        }

        .results-table { width: 100%; border-collapse: collapse; }

        .results-table thead tr {
            background: #f1f5f9;
        }

        .results-table th { 
            text-align: left; 
            padding: 13px 20px; 
            color: #64748b; 
            font-size: 7.5px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }

        .results-table tbody tr {
            transition: background 0.15s;
        }
        .results-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }

        .results-table td { 
            padding: 16px 20px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 10px;
            vertical-align: middle;
        }
        .results-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* date column */
        .col-date {
            font-size: 9px;
            color: #64748b;
            line-height: 1.5;
        }
        .col-date-time {
            font-size: 8px;
            color: #94a3b8;
        }

        /* candidate column */
        .student-name { color: #0f172a; font-size: 11px; line-height: 1.3; }
        .sub-text { color: #94a3b8; font-size: 8.5px; font-style: italic; margin-top: 2px; }

        /* test column */
        .test-title { font-size: 10px; color: #334155; }

        /* score column */
        .score-value {
            font-size: 15px;
            color: #0f172a;
            line-height: 1;
        }
        .score-denom {
            font-size: 9px;
            color: #94a3b8;
        }

        /* badges */
        .badge { 
            display: inline-block; 
            padding: 5px 11px; 
            border-radius: 100px; 
            font-size: 7.5px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            white-space: nowrap;
        }
        .badge-valid  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .badge-error  { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-neutral{ background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* ── Footer ───────────────────────────────────────── */
        .footer {
            position: fixed;
            bottom: 28px;
            left: 0;
            width: 100%;
            padding: 0 52px;
        }
        .footer-inner {
            border-top: 1px solid #e2e8f0;
            padding-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-brand {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .footer-brand span { color: #169BB6; }
        .footer-copy {
            font-size: 8px;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="page">

        <!-- ── Header ── -->
        <div class="header">
            <div class="header-left">
                <div class="logo-box">
                    <img src="{{ public_path('favicon.png') }}" class="logo-img" alt="SoliQuiz">
                </div>
                <div>
                    <div class="brand-name">Soli<span>Quiz</span></div>
                    <div class="brand-tagline">Intelligence Pédagogique</div>
                </div>
            </div>

            <div class="doc-meta">
                <div class="doc-label">Certificat de Résultats</div>
                <div class="doc-id">#{{ strtoupper(substr(uniqid(), -8)) }}</div>
                <div class="doc-date">{{ now()->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- ── Summary Card ── -->
        <div class="summary-card">
            <table class="summary-grid">
                <tr>
                    <td width="33%">
                        <span class="stat-label">Évaluation Pédagogique</span>
                        <span class="stat-value stat-highlight">{{ $qcmName ?? 'Rapport Global' }}</span>
                    </td>
                    <td width="33%">
                        <span class="stat-label">Cible / Cohorte</span>
                        <span class="stat-value">{{ $classeName ?? 'Toutes les classes' }}</span>
                    </td>
                    <td width="34%">
                        <span class="stat-label">Volume de Données</span>
                        <span class="stat-value">{{ $results->count() }} passages enregistrés</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="stat-label">Date du Rapport</span>
                        <span class="stat-value">{{ now()->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <span class="stat-label">Moyenne Générale</span>
                        <span class="stat-value">
                            @php $avg = $results->avg('score_obtenu'); @endphp
                            {{ $avg ? number_format($avg, 1) : '-' }} / 20
                        </span>
                    </td>
                    <td>
                        <span class="stat-label">Empreinte Numérique</span>
                        <span class="hash-value">{{ md5($results->toJson()) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ── Results Table ── -->
        <div class="section-title">Détail des Passages</div>
        <div class="table-wrapper">
            <table class="results-table">
                <thead>
                    <tr>
                        <th width="15%">Date &amp; Heure</th>
                        <th width="33%">Candidat</th>
                        <th width="20%">Test</th>
                        <th width="14%">Note Finale</th>
                        <th width="18%">Validation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr>
                            <td>
                                <div class="col-date">{{ $result->date_debut?->format('d/m/Y') ?? '-' }}</div>
                                <div class="col-date-time">{{ $result->date_debut?->format('H:i') ?? '' }}</div>
                            </td>
                            <td>
                                <div class="student-name">{{ $result->etudiant?->nom_complet ?? 'Étudiant Inconnu' }}</div>
                                <div class="sub-text">{{ $result->etudiant?->classe?->nom ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="test-title">{{ $result->qcm?->titre ?? '-' }}</div>
                            </td>
                            <td>
                                @if($result->score_obtenu !== null)
                                    <span class="score-value">{{ number_format($result->score_obtenu, 1) }}</span>
                                    <span class="score-denom"> /20</span>
                                @else
                                    <span class="score-value">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $label = $result->statut === 'reussi' ? 'Validé' : ($result->statut === 'echoue' ? 'Échec' : 'Abandon');
                                    $class = $result->statut === 'reussi' ? 'badge-valid' : ($result->statut === 'echoue' ? 'badge-error' : 'badge-neutral');
                                @endphp
                                <span class="badge {{ $class }}">{{ $label }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ── Footer ── -->
        <div class="footer">
            <div class="footer-inner">
                <div class="footer-brand"><span>Soli</span>Quiz Platform &bull; Intelligence Pédagogique Certifiée</div>
                <div class="footer-copy">Document généré automatiquement &copy; {{ date('Y') }}</div>
            </div>
        </div>

    </div>
</body>
</html>