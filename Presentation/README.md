---
marp: true
theme: default
_class: lead
_paginate: false
paginate: true
backgroundColor: #ffffff
style: |
  section {
    font-size: 22px;
    color: #333;
    line-height: 1.6;
    padding: 60px 80px;
  }
  footer { width: 100%; text-align: right; font-size: 14px; color: #888; }
  .logo-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 40px;   
    left: 60px;
    right: 60px;
  }
  .logo-header img { height: 140px; margin: 0; margin-left:10px; margin-right:10px }
  h1 { color: #3498DB; font-size: 2.8em; margin-top: 100px; text-align: left; }
  h2 { color: #3498DB; font-size: 2em; border-bottom: 2px solid #3498DB; margin-bottom: 40px;}
  h3 { text-align: left; color: #444; margin-top: 0; }

  .sommaire-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
  }
  .sommaire-item {
    display: flex;
    align-items: center;
    background: #f4faff;
    border-radius: 12px;
    padding: 15px 20px;
    border-left: 5px solid #3498DB;
  }
  .sommaire-num {
    background: #3498DB; color: white; width: 35px; height: 35px;
    display: flex; justify-content: center; align-items: center;
    border-radius: 50%; font-weight: bold; margin-right: 15px; flex-shrink: 0;
  }
  
  section > p > img {
    display: block;
    margin: 0 auto;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .dt-card {
    background: #f0f7fa;
    padding: 30px;
    border-radius: 10px;
    border-top: 6px solid #3498DB;
    text-align: left;
    margin-top: 20px;
    width: 100%;
  }

  /* --- FIX COULEURS TECH STACK --- */
  .tech-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
  }
  .badge-simple {
    padding: 8px 18px;
    border-radius: 6px;
    font-weight: 600;
    background-color: #545353ff;
    color: #ffffff !important;
    font-size: 0.85em;
    border: 1px solid #222;
  }

  .context-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 10px;
  }
  .context-card {
    background: #f4faff;
    border-radius: 10px;
    padding: 20px 25px;
    border-left: 5px solid #3498DB;
  }
  .context-card h4 { color: #3498DB; margin: 0 0 10px 0; }
  .problem-card {
    background: #fff5f5;
    border-left-color: #e74c3c;
  }
  .problem-card h4 { color: #e74c3c; }

  .persona-card {
    background: #ffffff;
    padding: 18px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border-top: 5px solid #3498DB;
    transition: transform 0.3s ease;
  }
  .persona-card strong { font-size: 1.1em; display: block; margin-bottom: 8px; }
  .persona-card p { font-size: 0.85em; margin: 0; color: #555; line-height: 1.4; }

  .ideation-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75em;
    font-weight: bold;
    margin-right: 5px;
    margin-bottom: 5px;
  }

  .demo-slide {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
  }
  .demo-icon {
    font-size: 5em;
    margin-bottom: 20px;
  }
  .demo-url {
    background: #3498DB;
    color: white;
    padding: 12px 30px;
    border-radius: 30px;
    font-size: 1.2em;
    font-weight: bold;
    margin-top: 20px;
    display: inline-block;
  }

---

<!-- PAGE DE GARDE -->

<div class="logo-header">
  <img src="images/ofppt-logo.png" alt="Logo Left">
  <img src="images/logo-solicode.png" alt="Logo Right">
</div>

# **Projet de Fin de Formation**
### Système de Gestion & d'Auto-évaluation QCM — **SoliQuiz**

**Réalisé par :** <span class="highlight">BENYEKHLEF Anouar</span>  
**Encadré par :** <span class="highlight">M. ESSARRAJ Fouad</span>  
**Filière :** Développement Mobile  
**Date de soutenance :** 12/06/2026



---

<!-- SOMMAIRE -->

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">01</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">02</div><div class="sommaire-text">Méthodologie de Travail</div></div>
  <div class="sommaire-item"><div class="sommaire-num">03</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">04</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">05</div><div class="sommaire-text">Conception</div></div>
  <div class="sommaire-item"><div class="sommaire-num">06</div><div class="sommaire-text">Démonstration</div></div>
</div>

---

<!-- 01 CONTEXTE DU PROJET -->

## 01. Contexte du projet

![h:430 Contexte](./images/contexte.jpg)

---

<!-- 02 METHODOLOGIE : DESIGN THINKING -->

## 02. Méthodologie : Design Thinking

![h:430 Design Thinking](./images/design-thinking.png)

---

## 02. Méthodologie : Scrum (Agile)

![h:490 Scrum](./images/scrum-process.png)

---

<!-- 03 BRANCHE FONCTIONNELLE : CADRAGE DU PROBLEME -->

## 03. Branche Fonctionnelle
### Cadrage du problème

<div class="dt-card" style="border-top-color: #e74c3c; background: linear-gradient(135deg, #fffafa 0%, #ffffff 100%); box-shadow: 0 10px 40px rgba(231, 76, 60, 0.08); padding: 40px; border-radius: 16px;">
  <h4 style="color: #e74c3c; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8em; margin-bottom: 15px;">Énoncé du problème</h4>
  <ul style="font-size: 1.05em; color: #2c3e50; line-height: 1.8; margin: 0; padding-left: 20px;">
    <li><strong>Aucun outil d'évaluation unifié</strong> à Solicode</li>
    <li>Outils dispersés : Google Forms, SoliLMS, Excel</li>
    <li><strong>Perte de temps</strong> et erreurs de saisie</li>
    <li>Absence de <strong>feedback pédagogique</strong> par objectif</li>
  </ul>
</div>

<br>

<div style="margin-top: 35px; background: #2c3e50; padding: 20px; border-radius: 10px; text-align: center;">
  <p style="margin: 0; color: #ecf0f1; font-size: 1em;">
    <strong style="color: #f1c40f;">HMW :</strong> Offrir un outil QCM intégré → correction auto + résultats par objectif + feedback immédiat
  </p>
</div>

---

<!-- 03 BRANCHE FONCTIONNELLE : CAS D'UTILISATION GLOBAL -->

## 03. Branche Fonctionnelle

## Branche Fonctionnelle : Cas d'utilisation (Web)

![w:1600 h:500 Ideation Global](./images/cas-utilisation-global-web.png)

---


## Branche Fonctionnelle : Partie Publique

![h:480 Partie Publique](./images/uc-public.png)

---


## Branche Fonctionnelle : Espace Formateur

![h:480 Espace Formateur](./images/uc-formateur.png)

---

## Branche Fonctionnelle : Espace Étudiant

![h:480 Espace Étudiant](./images/uc-etudiant.png)

---

## Branche Fonctionnelle : Espace Administrateur

![h:480 Espace Administrateur](./images/uc-administrateur.png)

---

## Branche Fonctionnelle : Cas d'utilisation (Mobile APK & API)

![h:480 Mobile & API](./images/cas-utilisation-global-mobile.png)

---

<!-- 04 BRANCHE TECHNIQUE -->

## 04. Branche Technique : Stack Technologique

<div>
  <div class="dt-card" style="margin-top:0; padding: 10px 20px; font-size: 0.7em; line-height: 1.2;">
    <h4 style="margin: 0 0 5px 0;">Back-end</h4>
    <ul style="margin: 0; padding-left: 20px;">
      <li style="margin-bottom: 2px;"><strong>PHP 8.2+</strong></li>
      <li style="margin-bottom: 2px;"><strong>Laravel 12</strong> (Framework MVC)</li>
      <li style="margin-bottom: 2px;"><strong>MySQL 8.0</strong> (Base de données)</li>
      <li style="margin-bottom: 2px;"><strong>PHP Native</strong> (Portage APK Mobile)</li>
      <li style="margin-bottom: 2px;"><strong>N8N</strong> (Automatisation de workflows)</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:5px; padding: 10px 20px; font-size: 0.7em; line-height: 1.2; border-top-color: #27ae60;">
    <h4 style="margin: 0 0 5px 0;">Front-end & Outils</h4>
    <ul style="margin: 0; padding-left: 20px;">
      <li style="margin-bottom: 2px;"><strong>Tailwind CSS</strong> (Utility-First CSS)</li>
      <li style="margin-bottom: 2px;"><strong>Preline UI</strong> (Composants UI)</li>
      <li style="margin-bottom: 2px;"><strong>Alpine.js</strong> (Interactions dynamiques)</li>
      <li style="margin-bottom: 2px;"><strong>Vite</strong> (Build Tooling)</li>
      <li style="margin-bottom: 2px;"><strong>Lucide Icons</strong> (Icônes SVG)</li>
    </ul>
  </div>
</div>

---

## 04. Branche Technique : Architecture

<div>
  <div class="dt-card" style="margin-top:0; padding: 10px 20px; font-size: 0.75em; line-height: 1.2; border-top-color: #8e44ad;">
    <h4 style="margin: 0 0 5px 0;">MVC (Model – View – Controller)</h4>
    <ul style="margin: 0; padding-left: 20px;">
      <li style="margin-bottom: 4px;"><strong>Model</strong> — Eloquent ORM, relations, accesseurs</li>
      <li style="margin-bottom: 4px;"><strong>View</strong> — Blade + Alpine.js + Tailwind</li>
      <li style="margin-bottom: 4px;"><strong>Controller</strong> — Orchestration des requêtes HTTP</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:10px; padding: 10px 20px; font-size: 0.75em; line-height: 1.2; border-top-color: #e67e22;">
    <h4 style="margin: 0 0 5px 0;">N-Tiers (Couche Service)</h4>
    <ul style="margin: 0; padding-left: 20px;">
      <li style="margin-bottom: 4px;"><strong>Controller</strong> → Validation & routing</li>
      <li style="margin-bottom: 4px;"><strong>Service</strong> → Logique métier (QCM, Scoring, Passation)</li>
      <li style="margin-bottom: 4px;"><strong>Repository / Model</strong> → Accès aux données</li>
    </ul>
  </div>
</div>

---

<!-- 05 CONCEPTION : DIAGRAMME DE CLASSES -->

## 05. Conception : Diagramme de classes

---

![w:1800 h:650 Diagramme de classe](./images/diagramme-classes-lr.png)

---

<!-- 06 DEMONSTRATION -->

## 06. Démonstration

<div class="demo-slide">
  <div class="demo-icon"></div>
  <h3 style="color: #3498DB; font-size: 1.8em; margin-bottom: 10px;">Démonstration en direct</h3>
  <p style="font-size: 1.1em; color: #555; max-width: 600px;">
    Parcours complet de la plateforme SoliQuiz — Création de QCM, passation, correction automatique et tableau de bord analytique.
  </p>
</div>

---

<!-- CONCLUSION -->

## Conclusion

- **Plateforme unifiée** d'évaluation QCM (Web + Mobile)
- **Correction automatique** et feedback par objectif
- **Architecture robuste** (MVC, N-Tiers, Services)
- **Méthodologie Agile** et Design Thinking

---

### Merci pour votre attention !