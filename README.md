# SoliQuiz — Système de Gestion & d'Auto-évaluation QCM

[![Laravel 12.x](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS 3.x](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![MySQL 8.0](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Vite](https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)

## Présentation du Projet
**SoliQuiz** est une solution complète de gestion des évaluations QCM développée pour le centre de formation **Solicode**. Elle vise à digitaliser le feedback pédagogique quotidien en offrant un écosystème unifié pour les formateurs et les apprenants.

L'objectif est de remplacer les workflows fragmentés par une plateforme unique capable de tracer l'acquisition des compétences par micro-objectif, d'automatiser la correction et de fournir une analyse granulaire des performances.

---

## Points Clés & Fonctionnalités
- **Performance & Mobile-First** : Interface ultra-rapide optimisée pour smartphone via Alpine.js et Tailwind CSS (Preline UI).
- **Analyse Granulaire** : Scores calculés par micro-objectif pédagogique pour un suivi précis.
- **Intégration SoliLMS** : Synchronisation fluide des données via API pour éliminer la double saisie administrative.
- **Auto-Correction & Feedback** : Résultats instantanés et corrections détaillées dès la validation du test.
- **Sécurité (RBAC)** : Gestion fine des accès (Admin, Formateur, Étudiant) basée sur Spatie Laravel Permission.

---

## Stack Technique
- **Backend** : PHP 8.4, Laravel 12.x (MVC & API REST).
- **Frontend** : Blade Templates, Tailwind CSS (Design System Preline), Alpine.js.
- **Build Tool** : Vite.
- **Base de données** : MySQL 8.
- **Mobile** : APK Android via NativePHP (Bridge).

---

## Structure de la Documentation
Explorez les différentes phases de conception du projet :
- [**Analyse**](./Analyse/) : Design Thinking (Empathie, Définition, Idéation), Cas d'utilisation.
- [**Maquettage**](./Maquettage/) : Kit Mobile, Mockups UI/UX, Wireframes.
- [**Presentation**](./Presentation/) : Slides Marp pour la soutenance technique.
- [**Rapport**](./Rapport/) : Documentation finale complète et synthèse du projet.

---

## Informations Académiques
> **Projet de Fin de Formation (PFE)** - Solicode Tangier.  
> **Auteur :** BENYEKHLEF Anouar  
> **Encadrant :** M. ESSARRAJ Fouad  
> **Année :** 2025/2026

---

## Installation Rapide (Dev)
```bash
# Servir l'application Laravel
php artisan serve

# Compiler les assets frontend
npm install
npm run dev
```
