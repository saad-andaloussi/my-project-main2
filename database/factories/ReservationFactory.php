<?php 

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Resource;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        // 1. On génère une date de début aléatoire (entre maintenant et +1 mois)
        $startsAt = Carbon::instance(fake()->dateTimeBetween('now', '+1 month'));
        
        // 2. On ajoute un nombre d'heures aléatoire (entre 1h et 72h) pour la date de fin
        $endsAt = (clone $startsAt)->addHours(fake()->numberBetween(1, 72));

        return [
            // On lie à un utilisateur et une ressource existante (ou on en crée)
            'user_id' => User::factory(),
            'resource_id' => Resource::factory(),
            
            'reason' => fake()->sentence(),
            'start_time' => $startsAt,
            'end_time' => $endsAt,
            'quantity' => fake()->numberBetween(1, 10),
            
            // Pas besoin de 'total_price' car ton modèle le calculera tout seul au moment du ->create() !
            
            'payment' => fake()->randomElement(['paid', 'unpaid', 'processing']),
            'status' => fake()->randomElement(['pending', 'approved', 'declined', 'cancelled']),
        ];
    }
}