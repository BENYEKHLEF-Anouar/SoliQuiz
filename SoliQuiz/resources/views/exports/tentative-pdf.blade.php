<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bilan Individuel - {{ $tentative->etudiant->nom_complet }}</title>
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
            margin-bottom: 36px;
            padding-bottom: 28px;
            border-bottom: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border-radius: 14px;
            overflow: hidden;
            /* box-shadow: 0 2px 10px rgba(22, 155, 182, 0.18); */
        }
        .logo-img { width: 100%; height: 100%; display: block; }

        .header-text { display: flex; flex-direction: column; gap: 4px; }

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

        .report-label {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            font-style: normal;
        }

        /* ── Info Grid ─────────────────────────────────────── */
        .grid { width: 100%; margin-bottom: 36px; border-collapse: separate; border-spacing: 0; }
        .grid td:first-child { padding-right: 10px; }
        .grid td:last-child  { padding-left:  10px; }

        .info-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 60%, #ecfdf5 100%);
            padding: 24px 28px;
            border-radius: 22px;
            border: 1.5px solid #e0f2fe;
            position: relative;
            overflow: hidden;
        }
        .info-card::after {
            content: '';
            position: absolute;
            bottom: -24px;
            right: -24px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(22, 155, 182, 0.06);
        }

        .label {
            font-size: 7.5px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 1.5px;
            margin-bottom: 6px;
            display: block;
        }
        .label + .label,
        .label.mt { margin-top: 18px; }

        .value { font-size: 13px; color: #0f172a; line-height: 1.3; }

        /* ── Performance Block ────────────────────────────── */
        .perf-container {
            margin-bottom: 40px;
            text-align: center;
        }

        .perf-circle { 
            display: inline-block; 
            padding: 28px 40px;
            min-width: 240px;
            border-radius: 22px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
        }

        .score-big {
            font-size: 48px;
            color: #0f172a;
            letter-spacing: -2px;
            margin-bottom: 8px;
            line-height: 1;
        }

        .status-text {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            display: inline-block;
            padding: 5px 14px;
            border-radius: 100px;
        }
        .text-valid { color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; }
        .text-fail  { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; }

        /* ── Section Title ─────────────────────────────────── */
        .section-title {
            font-size: 8px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 18px;
            padding-left: 4px;
        }

        /* ── Question Cards ─────────────────────────────────── */
        .question-card {
            margin-bottom: 22px;
            padding: 26px 28px;
            border-radius: 20px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            page-break-inside: avoid;
        }

        .question-meta {
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .q-num {
            background: #0f172a;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .q-body { flex: 1; }
        .q-text { font-size: 13px; color: #1e293b; line-height: 1.5; display: block; }
        .q-pts  { font-size: 8.5px; color: #94a3b8; margin-top: 5px; display: block; }

        /* ── Options ───────────────────────────────────────── */
        .option {
            padding: 13px 18px;
            border-radius: 14px;
            margin-top: 10px;
            border: 1.5px solid #f1f5f9;
            font-size: 11px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .opt-correct { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .opt-wrong   { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .opt-selected { border-left: 5px solid #169BB6; }

        .opt-text  { flex: 1; }
        .opt-badge {
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            flex-shrink: 0;
            padding: 3px 9px;
            border-radius: 100px;
        }
        .opt-badge-correct { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .opt-badge-wrong   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        /* ── Feedback ──────────────────────────────────────── */
        .feedback {
            margin-top: 20px;
            padding: 16px 20px;
            background: #f8fafc;
            border-radius: 14px;
            font-style: italic;
            color: #475569;
            font-size: 10px;
            border-left: 4px solid #169BB6;
            line-height: 1.6;
        }

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
            <div class="logo-box">
                <img src="{{ public_path('favicon.png') }}" class="logo-img" alt="SoliQuiz">
            </div>
            <div class="header-text">
                <div class="brand-name">Soli<span>Quiz</span></div>
                <div class="report-label">Bilan de Compétences Individuel</div>
            </div>
        </div>

        <!-- ── Info Grid ── -->
        <table class="grid">
            <tr>
                <td width="50%">
                    <div class="info-card">
                        <span class="label">Apprenant</span>
                        <div class="value">{{ $tentative->etudiant->nom_complet }}</div>
                        <span class="label mt">Classe / Cohorte</span>
                        <div class="value">{{ $tentative->etudiant->classe->nom }}</div>
                    </div>
                </td>
                <td width="50%">
                    <div class="info-card">
                        <span class="label">Évaluation Pédagogique</span>
                        <div class="value">{{ $tentative->qcm->titre }}</div>
                        <span class="label mt">Date de Validation</span>
                        <div class="value">{{ $tentative->date_fin->format('d/m/Y H:i') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- ── Performance Block ── -->
        @php $isSuccess = $tentative->score_obtenu >= $tentative->qcm->score_reussite; @endphp
        <div class="perf-container">
            <div class="perf-circle" style="border-color: {{ $isSuccess ? '#a7f3d0' : '#fecaca' }}; background: {{ $isSuccess ? '#f0fdf4' : '#fef2f2' }};">
                <div class="score-big">{{ number_format($tentative->score_obtenu, 1) }} / 20</div>
                <div class="status-text {{ $isSuccess ? 'text-valid' : 'text-fail' }}">
                    {{ $isSuccess ? 'Objectif Validé' : 'Objectif Non Atteint' }}
                </div>
            </div>
        </div>

        <!-- ── Questions ── -->
        <div class="section-title">Analyse Détaillée par Question</div>

        @foreach($questionDetails as $index => $question)
            <div class="question-card" style="border-left: 5px solid {{ $question->isCorrect ? '#10b981' : '#ef4444' }};">
                <div class="question-meta">
                    <span class="q-num">{{ $index + 1 }}</span>
                    <div class="q-body">
                        <span class="q-text">{{ $question->texte }}</span>
                        <span class="q-pts">{{ $question->points }} pts</span>
                    </div>
                </div>

                @foreach($question->options as $option)
                    @php
                        $isCorrect = $option->isSelected && $option->est_correcte;
                        $isWrong   = $option->isSelected && !$option->est_correcte;
                        $isMissed  = !$option->isSelected && $option->est_correcte;

                        $class = '';
                        if ($isCorrect || $isMissed) $class = 'opt-correct';
                        elseif ($isWrong) $class = 'opt-wrong';
                        if ($option->isSelected) $class .= ' opt-selected';

                        $style = ($isMissed && !$option->isSelected) ? 'opacity: 0.5; border-style: dashed;' : '';
                    @endphp
                    <div class="option {{ $class }}" style="{{ $style }}">
                        <span class="opt-text">{{ $option->texte }}</span>
                        @if($option->isSelected)
                            <span class="opt-badge {{ $option->est_correcte ? 'opt-badge-correct' : 'opt-badge-wrong' }}">
                                {{ $option->est_correcte ? 'Correct' : 'Faux' }}
                            </span>
                        @endif
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
            <div class="footer-inner">
                <div class="footer-brand">Bilan généré officiellement par <span>Soli</span>Quiz le {{ now()->format('d/m/Y à H:i') }}</div>
                <div class="footer-copy">&copy; {{ date('Y') }} Tous droits réservés.</div>
            </div>
        </div>

    </div>
</body>
</html>