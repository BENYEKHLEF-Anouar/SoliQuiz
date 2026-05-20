<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport de Résultats - SoliQuiz</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px;
            line-height: 1.4;
            color: #1e293b; 
            margin: 0; 
            padding: 0;
            background: #fff;
        }

        .page { padding: 30px; }

        /* ── Header Table ── */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .brand-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .brand-name span { color: #169BB6; }

        .brand-tagline {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 3px;
        }

        .doc-meta { text-align: right; }
        .doc-label {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3px;
        }
        .doc-id {
            font-size: 13px;
            font-weight: bold;
            color: #169BB6;
        }
        .doc-date {
            font-size: 9px;
            color: #64748b;
            margin-top: 3px;
        }

        /* ── Summary Card ── */
        .summary-card {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }

        .summary-grid { width: 100%; border-collapse: collapse; }
        .summary-grid td {
            padding: 8px 12px;
            vertical-align: top;
        }
        .summary-grid tr:first-child td { padding-bottom: 12px; }
        .summary-grid tr:last-child td { padding-top: 12px; border-top: 1px solid #e2e8f0; }

        .stat-label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 4px;
        }
        .stat-value { font-size: 12px; font-weight: bold; color: #0f172a; }
        .stat-highlight { color: #169BB6; }

        .hash-value {
            font-size: 8px;
            color: #94a3b8;
            letter-spacing: 0.5px;
            word-break: break-all;
        }

        /* ── Section Title ── */
        .section-title {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            font-weight: bold;
            border-left: 3px solid #169BB6;
            padding-left: 8px;
        }

        /* ── Results Table ── */
        .table-wrapper {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .results-table { width: 100%; border-collapse: collapse; }

        .results-table thead tr {
            background-color: #f1f5f9;
        }

        .results-table th { 
            text-align: left; 
            padding: 10px 14px; 
            color: #64748b; 
            font-size: 8px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .results-table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }

        .results-table td { 
            padding: 12px 14px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 10px;
            vertical-align: middle;
        }
        .results-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* date column */
        .col-date {
            font-size: 9.5px;
            color: #334155;
            font-weight: bold;
        }
        .col-date-time {
            font-size: 8.5px;
            color: #94a3b8;
        }

        /* candidate column */
        .student-name { color: #0f172a; font-size: 10.5px; font-weight: bold; }
        .sub-text { color: #94a3b8; font-size: 8.5px;  margin-top: 1px; }

        /* test column */
        .test-title { font-size: 10px; color: #334155; font-weight: bold; }

        /* score column */
        .score-value {
            font-size: 14px;
            color: #0f172a;
            font-weight: bold;
        }
        .score-denom {
            font-size: 9px;
            color: #94a3b8;
        }

        /* badges */
        .badge { 
            display: inline-block; 
            padding: 3px 10px; 
            border-radius: 20px; 
            font-size: 8px; 
            font-weight: bold;
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        .badge-valid  { background-color: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .badge-error  { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-neutral{ background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* ── Footer ── */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 30px;
            right: 30px;
            height: 30px;
        }
        .footer-table {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .footer-brand {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-brand span { color: #169BB6; }
        .footer-copy {
            font-size: 8px;
            color: #cbd5e1;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="page">

        <!-- ── Header Table ── -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand-name">Soli<span>Quiz</span></div>
                    <div class="brand-tagline">Intelligence Pédagogique</div>
                </td>
                <td class="doc-meta">
                    <div class="doc-label">Certificat de Résultats</div>
                    <div class="doc-id">#{{ strtoupper(substr(uniqid(), -8)) }}</div>
                    <div class="doc-date">{{ now()->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

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
                        <th width="12%">Date &amp; Heure</th>
                        <th width="28%">Candidat</th>
                        <th width="18%">Test</th>
                        <th width="14%">Durée</th>
                        <th width="14%">Note Finale</th>
                        <th width="14%">Validation</th>
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
                                @if($result->date_debut && $result->date_fin)
                                    @php
                                        $duration = $result->date_debut->diff($result->date_fin);
                                        $m = ($duration->h * 60) + $duration->i;
                                        $s = $duration->s;
                                    @endphp
                                    <div class="col-date">{{ $m > 0 ? $m.'m ' : '' }}{{ $s }}s</div>
                                @else
                                    <div class="col-date-time">-</div>
                                @endif
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
            <table class="footer-table">
                <tr>
                    <td class="footer-brand">
                        <span>Soli</span>Quiz Platform &bull; Intelligence Pédagogique Certifiée
                    </td>
                    <td class="footer-copy">
                        Document généré automatiquement &copy; {{ date('Y') }}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>