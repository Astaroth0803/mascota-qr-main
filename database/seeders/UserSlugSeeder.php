<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar slugs para todos los usuarios existentes que no tengan slug
        $users = User::whereNull('slug')->get();
        
        foreach ($users as $user) {
            $user->slug = $user->generateSlug();
            $user->save();
            echo "Slug generado para usuario: {$user->name} -> {$user->slug}\n";
        }
        
        echo "Se generaron slugs para {$users->count()} usuarios.\n";
    }
}
