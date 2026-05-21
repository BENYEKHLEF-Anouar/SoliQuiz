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
### Système de Gestion & d'Auto-évaluation QCM — **SoliQuiz**

**Réalisé par :** <span class="highlight">BENYEKHLEF Anouar</span>  
**Encadré par :** <span class="highlight">M. ESSARRAJ Fouad</span>  
**Filière :** Développement Mobile

---

## Sommaire

<div class="sommaire-grid">
  <div class="sommaire-item"><div class="sommaire-num">1</div><div class="sommaire-text">Méthodologie de travail</div></div>
  <div class="sommaire-item"><div class="sommaire-num">2</div><div class="sommaire-text">Branche Fonctionnelle</div></div>
  <div class="sommaire-item"><div class="sommaire-num">3</div><div class="sommaire-text">Branche Technique</div></div>
  <div class="sommaire-item"><div class="sommaire-num">4</div><div class="sommaire-text">Conception</div></div>
  <div class="sommaire-item"><div class="sommaire-num">5</div><div class="sommaire-text">Conclusion</div></div>
</div>

<!-- ---

## 1. Contexte du projet

![w:700 h:470 Context](./images/contexte.png)
![w:700 h:470 Context](./images/contexte.jpg) -->


---

## 1. Méthodologie : Design Thinking



![h:430 Design Thinking](./images/design-thinking.png)

---

## Méthodologie : Scrum (Agile)

![h:490 Scrum](./images/scrum-process.png)

---
<!-- 
## Méthodologie : Processus 2TUP

![w:700 h:450 2TUP](./images/2tup.png)

--- -->
<!-- 
## 3. Branche Fonctionnelle : Design Thinking

### 1. EMPATHIE :

--- -->

<!-- ## 3. Branche Fonctionnelle : Design Thinking -->
<!-- ### 1. EMPATHIE : Formateur (Youssef) -->

<!-- ![w:1300 h:600 Carte Empathie Youssef](./images/carte-empathie-formateur-youssef-nouvelle.png)

--- -->

<!-- ## 3. Branche Fonctionnelle : Design Thinking -->
<!-- ### 1. EMPATHIE : Formatrice (Fatine) -->

<!-- ![w:1300 h:600 Carte Empathie Fatine](./images/carte-empathie-formatrice-fatine-nouvelle.png)

--- -->

<!-- ## 3. Branche Fonctionnelle : Design Thinking -->
<!-- ### 1. EMPATHIE : Apprenant (Soufiane) -->

<!-- ![w:1300 h:600 Carte Empathie Soufiane](./images/carte-empathie-apprenant-soufiane-nouvelle.png)

--- -->

<!-- ## 3. Branche Fonctionnelle : Design Thinking -->
<!-- ### 1. EMPATHIE : Apprenant (Mehdi) -->

<!-- ![w:1300 h:600 Carte Empathie Mehdi](./images/carte-empathie-apprenant-mehdi-nouvelle.png)

--- -->

<!-- ## 3. Branche Fonctionnelle : Design Thinking -->
<!-- ### 1. EMPATHIE : Administrateur (Fouad) -->

<!-- ![w:1300 h:600 Carte Empathie Fouad](./images/carte-empathie-admin-fouad-nouvelle.png)

--- -->

## 2. Branche Fonctionnelle : Design Thinking
### 2. DÉFINITION : Cadrage du problème

<div class="dt-card" style="border-top-color: #e74c3c; background: linear-gradient(135deg, #fffafa 0%, #ffffff 100%); box-shadow: 0 10px 40px rgba(231, 76, 60, 0.08); padding: 40px; border-radius: 16px;">
  <h4 style="color: #e74c3c; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8em; margin-bottom: 15px;">Énoncé du problème</h4>
  <p style="font-size: 1.1em; line-height: 1.6; color: #2c3e50; margin: 0;">
    Les acteurs de Solicode <strong>ne disposent d'aucun outil d'évaluation unifié</strong>, les obligeant à jongler entre Google Forms, SoliLMS et Excel — entraînant une <strong>perte de temps, des erreurs de saisie et une absence de feedback pédagogique exploitable</strong> par objectif.
  </p>
</div>

<br>

<div style="margin-top: 35px; background: #2c3e50; padding: 20px; border-radius: 10px; text-align: center;">
  <p style="margin: 0; font-style: italic; color: #ecf0f1; font-size: 1em;">
    <strong style="color: #f1c40f;">How Might We :</strong> Comment pourrions-nous offrir un outil d'évaluation QCM intégré qui automatise la correction, structure les résultats par objectif et fournit un feedback immédiat ?
  </p>
</div>

---

## Branche Fonctionnelle : Design Thinking

### 3. IDÉATION : Cas d'utilisation (Plateforme Web & Application Mobile)

<!-- --- -->
<!-- ## Branche Fonctionnelle : Design Thinking -->
<!-- ### 3. IDÉATION : Cas d'utilisation (Plateforme Web & Application Mobile) -->

<!-- ![w:1000 h:500 Ideation Global](./images/cas-utilisation-global.png) -->


---
## Branche Fonctionnelle : Cas d'utilisation (Web)
<!-- ### 3. IDÉATION : Cas d'utilisation (Plateforme Web) -->

![w:1600 h:500 Ideation Global](./images/cas-utilisation-global-web.png)

---

## Branche Fonctionnelle : Cas d'utilisation (Apk)
<!-- ### 3. IDÉATION : Cas d'utilisation (Application Mobile) -->

![w:1600 h:500 Ideation Global](./images/cas-utilisation-global-mobile.png)

<!-- ---

## Branche Fonctionnelle : Cas d'utilisation — Sprint 1 MVP

![w:1000 h:600 Use Case Sprint 1](./images/cas-utilisation-sprint-1-mvp.png) -->

<!-- ---

## Branche Fonctionnelle : Cas d'utilisation — Sprint 2 Avancé

![w:1000 h:500 Use Case Sprint 2](./images/cas-utilisation-sprint-2-avancé.png) -->

---

## Branche Fonctionnelle : Maquettage (Public Landing)

![h:500 Public Landing](./images/public-landing(2).png)

<!-- ---

## Branche Fonctionnelle : Maquettage (Admin Dashboard)

![h:450 Admin Dashboard](./images/admin-dashboard.png) -->

---

## Branche Fonctionnelle : Maquettage (Formateur Dashboard)

![h:450 Formateur Dashboard](./images/formateur-dashboard(2).png)

---

## Branche Fonctionnelle : Maquettage (Apprenant Dashboard)

![h:450 Apprenant Dashboard](./images/apprenant-dashboard(2).png)

---

## Branche Fonctionnelle : Maquettage (Mobile Dashboard)

![h:450 Mobile Dashboard](./images/mobile-dashboard(2).png)

---

## 3. Branche Technique : Tech Stack
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
      <!-- <li><strong>Tiptap</strong> (Éditeur de questions riches)</li> -->
      <li><strong>Vite</strong> (Build Tooling)</li>
    </ul>
  </div>
</div>

---


## 4. Conception : Diagramme de classes

---


![w:1800 h:650 Diagramme de classe](./images/diagramme-classes-lr(2).png)

---

## 5. Conclusion

<!-- ![h:450 Conclusion](./images/conclusion.png) -->

<br>

---

### Merci pour votre attention ! 