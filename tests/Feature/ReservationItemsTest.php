<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\SpaPackage;
use App\Models\StockItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_services_and_stock_items_are_saved_and_returned(): void
    {
        $this->actingAs(User::factory()->create());
        $first = $this->package('Swedish Massage', 95);
        $second = $this->package('Aromatherapy Massage', 125);
        $stock = StockItem::create([
            'code' => 'OIL-01',
            'name' => 'Masaj Yağı',
            'sale_price' => 450,
            'status' => 'aktif',
        ]);

        $response = $this->postJson('/api/reservations', $this->payload([
            ['type' => 'package', 'id' => $first->id],
            ['type' => 'package', 'id' => $second->id],
            ['type' => 'stock', 'id' => $stock->id],
        ]));

        $response->assertCreated()->assertJsonCount(3, 'data.items');
        $reservationId = $response->json('data.id');
        $this->assertDatabaseHas('reservation_items', [
            'reservation_id' => $reservationId,
            'spa_package_id' => $first->id,
            'name' => 'Swedish Massage',
            'currency' => 'EUR',
        ]);
        $this->assertDatabaseHas('reservation_items', [
            'reservation_id' => $reservationId,
            'stock_item_id' => $stock->id,
            'name' => 'Masaj Yağı',
            'currency' => 'TRY',
        ]);

        $this->getJson('/api/reservations?start=2026-09-01&end=2026-10-01')
            ->assertOk()
            ->assertJsonCount(3, 'data.reservations.0.items');

        $this->getJson("/api/reservations/{$reservationId}")
            ->assertOk()
            ->assertJsonPath('data.id', $reservationId)
            ->assertJsonCount(3, 'data.items');
    }

    public function test_updating_a_reservation_replaces_its_selected_items(): void
    {
        $this->actingAs(User::factory()->create());
        $old = $this->package('Eski Hizmet', 80);
        $new = $this->package('Yeni Hizmet', 110);
        $reservation = Reservation::create($this->reservationAttributes());
        $reservation->items()->create([
            'spa_package_id' => $old->id,
            'type' => 'package',
            'name' => $old->name,
            'unit_price' => $old->price,
            'currency' => 'EUR',
        ]);

        $this->putJson("/api/reservations/{$reservation->id}", $this->payload([
            ['type' => 'package', 'id' => $new->id],
        ]))->assertOk()->assertJsonCount(1, 'data.items');

        $this->assertDatabaseMissing('reservation_items', [
            'reservation_id' => $reservation->id,
            'spa_package_id' => $old->id,
        ]);
        $this->assertDatabaseHas('reservation_items', [
            'reservation_id' => $reservation->id,
            'spa_package_id' => $new->id,
        ]);
    }

    private function package(string $name, float $price): SpaPackage
    {
        return SpaPackage::create([
            'name' => $name,
            'duration_text' => '60 Dakika',
            'featured_contents' => $name,
            'target_audience' => 'Tüm misafirler',
            'price' => $price,
        ]);
    }

    private function payload(array $items): array
    {
        return array_merge($this->reservationAttributes(), [
            'items_json' => json_encode($items),
        ]);
    }

    private function reservationAttributes(): array
    {
        return [
            'guest_name' => 'Deniz Yılmaz',
            'phone' => '0532 000 00 01',
            'service_name' => 'Swedish Massage',
            'reservation_date' => '2026-09-21',
            'start_time' => '09:30',
            'end_time' => '10:20',
            'status' => 'planned',
            'notes' => null,
        ];
    }
}
