<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bilan Individuel - {{ $tentative->etudiant->nom_complet }}</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px;
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

        /* ── Question Cards ── */
        .question-card {
            margin-bottom: 20px;
            padding: 18px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            page-break-inside: avoid;
        }

        /* ── Feedback ── */
        .feedback {
            margin-top: 12px;
            padding: 10px 15px;
            background: #f8fafc;
            border-radius: 8px;
            
            color: #475569;
            font-size: 9.5px;
            border-left: 3px solid #169BB6;
        }

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
                    <div class="brand-tagline">Bilan Individuel</div>
                </td>
                <td class="doc-meta">
                    <div class="doc-label">Bilan de Passation</div>
                    <div class="doc-id">#{{ strtoupper(substr(uniqid(), -8)) }}</div>
                    <div class="doc-date">{{ $tentative->date_fin->format('d/m/Y H:i') }}</div>
                </td>
            </tr>
        </table>

        <!-- ── Summary Card ── -->
        @php $isSuccess = $tentative->score_obtenu >= $tentative->qcm->score_reussite; @endphp
        <div class="summary-card">
            <table class="summary-grid">
                <tr>
                    <td width="33%">
                        <span class="stat-label">Apprenant</span>
                        <span class="stat-value stat-highlight">{{ $tentative->etudiant->nom_complet }}</span>
                    </td>
                    <td width="33%">
                        <span class="stat-label">Classe / Cohorte</span>
                        <span class="stat-value">{{ $tentative->etudiant->classe->nom }}</span>
                    </td>
                    <td width="34%">
                        <span class="stat-label">Évaluation Pédagogique</span>
                        <span class="stat-value">{{ $tentative->qcm->titre }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="stat-label">Date de Validation</span>
                        <span class="stat-value">{{ $tentative->date_fin->format('d/m/Y H:i') }}</span>
                    </td>
                    <td>
                        <span class="stat-label">Note Finale</span>
                        <span class="stat-value stat-highlight" style="font-size: 14px;">
                            {{ number_format($tentative->score_obtenu, 1) }} <span style="font-size: 9px; color: #94a3b8; font-weight: normal;">/ 20</span>
                        </span>
                    </td>
                    <td>
                        <span class="stat-label">Statut de Validation</span>
                        <span class="badge {{ $isSuccess ? 'badge-valid' : 'badge-error' }}">
                            {{ $isSuccess ? 'Objectif Validé' : 'Objectif Non Atteint' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- ── Questions ── -->
        <div class="section-title">Analyse Détaillée par Question</div>

        @foreach($questionDetails as $index => $question)
            <div class="question-card" style="border-left: 5px solid {{ $question->isCorrect ? '#10b981' : '#ef4444' }};">
                <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 28px; vertical-align: top;">
                            <div style="background: #0f172a; color: #fff; width: 22px; height: 22px; border-radius: 6px; text-align: center; line-height: 22px; font-weight: bold; font-size: 11px;">
                                {{ $index + 1 }}
                            </div>
                        </td>
                        <td style="vertical-align: top; padding-left: 8px;">
                            <span style="font-size: 12px; font-weight: bold; color: #1e293b; line-height: 1.4;">{{ $question->texte }}</span>
                            <span style="font-size: 8.5px; color: #94a3b8; margin-top: 3px; display: block;">{{ $question->points }} pts</span>
                        </td>
                    </tr>
                </table>

                @foreach($question->options as $option)
                    @php
                        $isCorrect = $option->isSelected && $option->est_correcte;
                        $isWrong   = $option->isSelected && !$option->est_correcte;
                        $isMissed  = !$option->isSelected && $option->est_correcte;

                        $bgColor = '#fff';
                        $borderColor = '#e2e8f0';
                        $textColor = '#475569';
                        
                        if ($isCorrect || $isMissed) {
                            $bgColor = '#f0fdf4';
                            $borderColor = '#bbf7d0';
                            $textColor = '#166534';
                        } elseif ($isWrong) {
                            $bgColor = '#fef2f2';
                            $borderColor = '#fecaca';
                            $textColor = '#991b1b';
                        }
                        
                        $borderLeft = $option->isSelected ? '5px solid #169BB6' : '1px solid ' . $borderColor;
                        $style = ($isMissed && !$option->isSelected) ? 'opacity: 0.6; border-style: dashed;' : '';
                    @endphp
                    <div style="padding: 10px 14px; border-radius: 8px; border: 1px solid {{ $borderColor }}; border-left: {{ $borderLeft }}; background-color: {{ $bgColor }}; color: {{ $textColor }}; margin-bottom: 6px; font-size: 10.5px; {{ $style }}">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="vertical-align: middle;">{{ $option->texte }}</td>
                                @if($option->isSelected)
                                    <td style="text-align: right; width: 60px; vertical-align: middle;">
                                        <span style="display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase; background-color: {{ $option->est_correcte ? '#ecfdf5' : '#fef2f2' }}; color: {{ $option->est_correcte ? '#059669' : '#dc2626' }}; border: 1px solid {{ $option->est_correcte ? '#a7f3d0' : '#fecaca' }};">
                                            {{ $option->est_correcte ? 'Correct' : 'Faux' }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        </table>
                    </div>
                @endforeach

                @if($question->explication)
                    <div class="feedback">
                        <strong>Note Pédagogique :</strong> {{ $question->explication }}
                    </div>
                @endif
            </div>
        @endforeach

        <!-- ── Footer ── -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-brand">
                        Bilan généré officiellement par <span>Soli</span>Quiz le {{ now()->format('d/m/Y à H:i') }}
                    </td>
                    <td class="footer-copy">
                        &copy; {{ date('Y') }} Tous droits réservés.
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>