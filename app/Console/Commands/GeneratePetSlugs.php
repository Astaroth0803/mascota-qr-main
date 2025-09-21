<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pet;
use Illuminate\Support\Str;

class GeneratePetSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pets:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera slugs para todas las mascotas existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generando slugs para mascotas existentes...');

        $pets = Pet::whereNull('slug')->orWhere('slug', '')->get();
        $count = 0;

        foreach ($pets as $pet) {
            $slug = Str::slug($pet->nombre);
            $originalSlug = $slug;
            $counter = 1;

            // Verificar que el slug sea único
            while (Pet::where('slug', $slug)->where('id', '!=', $pet->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $pet->slug = $slug;
            $pet->save();

            $this->line("✓ {$pet->nombre} → {$slug}");
            $count++;
        }

        $this->info("¡Completado! Se generaron {$count} slugs.");
    }
}
