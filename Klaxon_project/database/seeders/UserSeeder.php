<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // hash de "password"
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

        DB::table('users')->upsert([
            ['name' => 'Admin Principal', 'email' => 'admin@entreprise.com',           'password' => $password, 'role' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Julien Martin',   'email' => 'julien.martin@entreprise.com',   'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Camille Leroy',   'email' => 'camille.leroy@entreprise.com',   'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nina Dupont',     'email' => 'nina.dupont@entreprise.com',     'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Omar Bensaid',    'email' => 'omar.bensaid@entreprise.com',    'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lea Bernard',     'email' => 'lea.bernard@entreprise.com',     'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hugo Pereira',    'email' => 'hugo.pereira@entreprise.com',    'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sofia Moreau',    'email' => 'sofia.moreau@entreprise.com',    'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Antoine Caron',   'email' => 'antoine.caron@entreprise.com',   'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Maya Robin',      'email' => 'maya.robin@entreprise.com',      'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yanis Gauthier',  'email' => 'yanis.gauthier@entreprise.com',  'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claire Fontaine', 'email' => 'claire.fontaine@entreprise.com', 'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thomas Lambert',  'email' => 'thomas.lambert@entreprise.com',  'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ines Garcia',     'email' => 'ines.garcia@entreprise.com',     'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lucas Marchand',  'email' => 'lucas.marchand@entreprise.com',  'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Eva Renard',      'email' => 'eva.renard@entreprise.com',      'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Quentin Lefevre', 'email' => 'quentin.lefevre@entreprise.com', 'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Aicha Benali',    'email' => 'aicha.benali@entreprise.com',    'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Romain Dubois',   'email' => 'romain.dubois@entreprise.com',   'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manon Chevalier', 'email' => 'manon.chevalier@entreprise.com', 'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pierre Legrand',  'email' => 'pierre.legrand@entreprise.com',  'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Zoe Blanchard',   'email' => 'zoe.blanchard@entreprise.com',   'password' => $password, 'role' => 'user',  'created_at' => $now, 'updated_at' => $now],
        ], ['email'], ['name','password','role','updated_at']);
    }
}
