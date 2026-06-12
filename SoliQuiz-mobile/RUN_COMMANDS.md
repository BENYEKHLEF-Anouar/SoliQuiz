# Commandes pour lancer les applications SoliQuiz

## 1. Application Backend (SoliQuiz)

Exécutez dans le dossier `SoliQuiz` :
```powershell
php artisan serve
npm run dev
```

## 2. Application Mobile (SoliQuiz-mobile)

### Étape A : Lancer l'émulateur Android
```powershell
C:\Users\anoua\AppData\Local\Android\Sdk\emulator\emulator.exe -avd Pixel_7a
```

### Étape B : Compiler et déployer le projet
Exécutez dans le dossier `SoliQuiz-mobile` :
```powershell
# Compiler les assets frontend
npm run build

# Configurer les variables d'environnement temporaires et démarrer NativePHP
$env:JAVA_HOME = "C:\Program Files\Android\Android Studio\jbr"
$env:PATH += ";C:\Users\anoua\AppData\Local\Android\Sdk\platform-tools;C:\Program Files\Android\Android Studio\jbr\bin"
php artisan native:run android
```
