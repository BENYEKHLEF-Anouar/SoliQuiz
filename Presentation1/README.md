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
  h1 { color: #088dc7; font-size: 2.8em; margin-top: 100px; text-align: left; }
  h2 { color: #088dc7; font-size: 2em; border-bottom: 2px solid #088dc7; margin-bottom: 40px;}
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
    border-left: 5px solid #088dc7;
  }
  .sommaire-num {
    background: #088dc7; color: white; width: 35px; height: 35px;
    display: flex; justify-content: center; align-items: center;
    border-radius: 50%; font-weight: bold; margin-right: 15px; flex-shrink: 0;
  }
  
  .img-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
  }
  .img-methodo {
    width: 85%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .dt-card {
    background: #f0f7fa;
    padding: 30px;
    border-radius: 10px;
    border-top: 6px solid #088dc7;
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
    background-color: #545353ff; /* Gris foncé unique */
    color: #ffffff !important;
    font-size: 0.85em;
    border: 1px solid #222;
  }
  .maquette-grid {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: flex-start;
    height: 350px;
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
    border-left: 5px solid #088dc7;
  }
  .context-card h4 { color: #088dc7; margin: 0 0 10px 0; }
  .problem-card {
    background: #fff5f5;
    border-left-color: #e74c3c;
  }
  .problem-card h4 { color: #e74c3c; }

  /* New Premium Cards for Empathie/Ideation */
  .persona-card {
    background: #ffffff;
    padding: 18px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border-top: 5px solid #088dc7;
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

---


<div class="logo-header">
  <img src="images/ofppt-logo.png" alt="Logo Left">
  <img src="images/logo-solicode.png" alt="Logo Right">
</div>

# **Projet de Fin de Formation**
### Système de QCM Interactif — **SoliQuiz**

**Réalisé par :** <span class="highlight">BENYEKHLEF Anouar</span>  
**Encadré par :** <span class="highlight">M. ESSARRAJ Fouad</span>  
**Filière :** Développement Mobile et Web

---

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Contexte du projet</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Méthodologie de travail</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conception</div></div>
  <div class="sommaire-item"><div class="sommaire-num">6</div><div class="sommaire-text">Démonstration</div></div>
  <div class="sommaire-item"><div class="sommaire-num">7</div><div class="sommaire-text">Conclusion</div></div>
</div>

---

## 1. Contexte du projet

<div class="context-grid">
  <div class="context-card">
    <h4>Contexte</h4>
    <p>Les formateurs de Solicode font face à une gestion administrative lourde : double saisie, correction manuelle et manque de visibilité sur l'acquisition des compétences.</p>
    <p>Ce projet analyse leurs besoins pour proposer une solution optimisant leur workflow et leur performance professionnelle.</p>
  </div>

  <div class="context-card">
    <h4>Cadre du Projet</h4>
    <p>Projet de fin de formation visant à centraliser les évaluations et supprimer les frictions entre Google Forms et SoliLMS.</p>
    <p><strong>SoliQuiz</strong> automatise le scoring par micro-objectif et synchronise les notes pour un suivi pédagogique précis.</p>
  </div>
</div>

---

## 2. Méthodologie : Design Thinking



<div class="img-container">
  <img src="images/design-thinking.png" class="img-methodo" alt="Design Thinking">
</div>

---

## Méthodologie : Scrum (Agile)

<div class="img-container">
  <img src="images/scrum-process.jpg" class="img-methodo" alt="Scrum">
</div>

---

## Méthodologie : Processus 2TUP

<div class="img-container">
  <img src="images/2tup.png" class="img-methodo" alt="2TUP">
</div>

---

## 3. Branche Fonctionnelle : Design Thinking
### 1. EMPATHIE : Comprendre l'utilisateur

<div style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-top: 10px;">
  <div class="persona-card" style="border-top-color: #088dc7; background: linear-gradient(180deg, #f0f7ff 0%, #ffffff 100%);">
    <strong style="color: #088dc7;">Youssef (Formateur)</strong>
    <p>Passe 40% de son temps à corriger manuellement des QCM Google Forms et à recopier les notes sur SoliLMS. Il veut automatiser son suivi pédagogique mais n'a aucun outil pour lier ses questions aux objectifs du bootcamp.</p>
  </div>
  
  <div class="persona-card" style="border-top-color: #e74c3c; background: linear-gradient(180deg, #fff5f5 0%, #ffffff 100%);">
    <strong style="color: #e74c3c;">Soufiane (Étudiant)</strong>
    <p>Cherche à comprendre ses lacunes après chaque test mais ne reçoit que des scores bruts (ex: 12/20) sans explication. Il finit par réviser au hasard car il n'a aucun feedback détaillé sur les compétences non acquises.</p>
  </div>
  
  <div class="persona-card" style="border-top-color: #27ae60; background: linear-gradient(180deg, #f0fff4 0%, #ffffff 100%);">
    <strong style="color: #27ae60;">Fouad (Administrateur)</strong>
    <p>Doit superviser les performances de plusieurs cohortes sans aucun tableau de bord centralisé. Il est incapable de détecter les décrochages en temps réel car les données sont dispersées entre Excel et Facebook.</p>
  </div>
</div>

---

## Branche Fonctionnelle : Design Thinking
### 2. DÉFINITION : Cadrage du problème

<div class="dt-card" style="border-top-color: #e74c3c; background: linear-gradient(135deg, #fffafa 0%, #ffffff 100%); box-shadow: 0 10px 40px rgba(231, 76, 60, 0.08); padding: 40px; border-radius: 16px;">
  <h4 style="color: #e74c3c; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8em; margin-bottom: 15px;">Point de Vue (POV)</h4>
  <p style="font-size: 1.1em; line-height: 1.6; color: #2c3e50; margin: 0;">
    "Les formateurs et apprenants de Solicode subissent une <strong>fracture numérique</strong> entre Google Forms, Excel et SoliLMS, provoquant une perte de temps administrative et une opacité pédagogique totale."
  </p>
</div>


<br>

<div style="margin-top: 35px; background: #2c3e50; padding: 20px; border-radius: 10px; text-align: center;">
  <p style="margin: 0; font-style: italic; color: #ecf0f1; font-size: 1em;">
    <strong style="color: #f1c40f;">How Might We :</strong> Créer un pont numérique qui automatise l'évaluation et révèle les compétences en temps réel ?
  </p>
</div>

---

## Branche Fonctionnelle : Design Thinking
### 3. IDÉATION
#### Solutions retenues

<div style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-top: 10px;">
  <div class="persona-card" style="border-top-color: #088dc7; padding: 15px;">
    <strong>~ Plateforme centralisée (Web & APK) de gestion des QCM.</strong>
  </div>
  <div class="persona-card" style="border-top-color: #27ae60; padding: 15px;">
    <strong>~ Création et structuration des tests par micro-objectifs pédagogiques.</strong>
  </div>
  <div class="persona-card" style="border-top-color: #f39c12; padding: 15px;">
    <strong>~ Passation sécurisée avec feedback immédiat pour les étudiants.</strong>
  </div>
  <div class="persona-card" style="border-top-color: #9b59b6; padding: 15px;">
    <strong>~ Tableau de bord de suivi et synchronisation automatique vers SoliLMS.</strong>
  </div>
</div>

---

## Branche Fonctionnelle : Cas d'utilisation

<div class="img-container">
  <h3>Interaction Utilisateur — Vue globale (UML)</h3>
  <img src="images/cas-utilisation-global.png" class="img-methodo" alt="Use Case Global">
</div>

---

## Branche Fonctionnelle : Cas d'utilisation — Sprint 1 MVP

<div class="img-container">
  <img src="images/cas-utilisation-sprint-1-mvp.png" class="img-methodo" alt="Use Case Sprint 1">
</div>

---

## Branche Fonctionnelle : Cas d'utilisation — Sprint 2 Avancé

<div class="img-container">
  <img src="images/cas-utilisation-sprint-2-avance.png" class="img-methodo" alt="Use Case Sprint 2">
</div>

---

## Branche Fonctionnelle : Maquettes (UI/UX)

<div class="maquette-grid">
  <div style="text-align: center;">
    <img src="images/maquette.png" class="img-methodo" style="height: 360px; width: auto;" alt="Maquette Desktop">
    <p style="font-size: 0.3rem; color: #666;">Interface Administration</p>
  </div>
</div>

---

## 4. Branche Technique : Tech Stack
<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h4>Back-end & Architecture</h4>
    <ul>
      <li><strong>PHP 8.2+ / Laravel 12</strong> (Framework MVC)</li>
      <li><strong>MySQL 8.0</strong> (Persistance des données)</li>
      <li><strong>Native PHP</strong> (Portage APK Mobile)</li>
      <li><strong>Spatie</strong> (Gestion des rôles & permissions)</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #27ae60;">
    <h4>Front-end & Outils</h4>
    <ul>
      <li><strong>Tailwind CSS & Preline</strong> (Mobile-First)</li>
      <li><strong>Alpine.js</strong> (Interactions dynamiques / Timer)</li>
      <li><strong>Tiptap</strong> (Éditeur de questions riches)</li>
      <li><strong>Vite</strong> (Build Tooling)</li>
    </ul>
  </div>
</div>

---


## 5. Conception : Diagramme de classe

 <h3>Modélisation des données (MLD)</h3>
<div class="img-container">
 
  <img src="images/diagramme-class.png" style="width: 100%;" alt="Diagramme de classe">
</div>

---

## 6. Démonstration : Environnement & Outils

<div class="sommaire-grid">
  <div class="dt-card" style="margin-top:0;">
    <h4>Environnement de Développement</h4>
    <ul>
      <li><strong>IDE :</strong> VS Code & Antigravity</li>
      <li><strong>Monitoring DB :</strong> MySQL Workbench</li>
      <li><strong>Navigateur :</strong> Chrome DevTools</li>
    </ul>
  </div>
  <div class="dt-card" style="margin-top:0; border-top-color: #27ae60;">
    <h4>Gestion & Déploiement</h4>
    <ul>
      <li><strong>Modélisation UML :</strong> Mermaid / PlantUML</li>
      <li><strong>Gestion de version :</strong> Git (GitHub)</li>
    </ul>
  </div>
</div>

<br>

---

## 7. Conclusion

<div style="display: grid; grid-template-columns: 1fr; gap: 12px; margin-top: 10px;">
  <div class="persona-card" style="border-top-color: #27ae60; padding: 15px;">
    <strong>~ Objectifs :</strong> Solution QCM centralisée, Mobile-First et interconnectée (SoliLMS).
  </div>
  <div class="persona-card" style="border-top-color: #088dc7; padding: 15px;">
    <strong>~ Expertise :</strong> Maîtrise du cycle Agile, de 2TUP et de l'écosystème Full-stack Laravel.
  </div>
  <div class="persona-card" style="border-top-color: #f39c12; padding: 15px;">
    <strong>~ Impact :</strong> Suppression de la double saisie et apport d'un feedback granulaire par micro-objectif.
  </div>
  <div class="persona-card" style="border-top-color: #9b59b6; padding: 15px;">
    <strong>~ Demain :</strong> Intelligence Artificielle pour la génération et l'analyse prédictive des tests.
  </div>
</div>

<br>

---

### Merci pour votre attention ! 