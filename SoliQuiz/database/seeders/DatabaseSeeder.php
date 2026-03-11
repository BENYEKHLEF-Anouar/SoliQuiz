<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver temporairement les contraintes de clés étrangères
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vider les tables dans l'ordre inverse
        DB::table('choix_reponses')->truncate();
        DB::table('reponses')->truncate();
        DB::table('tentatives')->truncate();
        DB::table('options')->truncate();
        DB::table('questions')->truncate();
        DB::table('qcms')->truncate();
        DB::table('competences')->truncate();
        DB::table('unites_apprentissage')->truncate();
        DB::table('seances')->truncate();
        DB::table('classes')->truncate();
        DB::table('users')->truncate();

        // Réactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Appel de tous les seeders dans l'ordre logique d'intégrité référentielle
        $this->call([
            UserSeeder::class,
            ClasseSeeder::class,
            UserClasseSeeder::class,
            SeanceSeeder::class,
            UniteApprentissageSeeder::class,
            CompetenceSeeder::class,
            QCMSeeder::class,
            QuestionSeeder::class,
            OptionSeeder::class,
            TentativeSeeder::class,
            ReponseSeeder::class,
            ChoixReponseSeeder::class,
        ]);

        $this->command->info('Base de données initialisée via les Seeders individuels avec succès !');
    }
}
