<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a user', function () {
    
    User::factory()->create([
        'role' => UserRole::ADMIN,
        'email' => 'admin@example.com',
    ]);

    $response = $this->postJson('/api/users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertCreated()->assertJsonStructure([
        'id', 'email', 'name', 'created_at'
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

test('returns users with orders_count', function () {

    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user = User::factory()->hasOrders(3)->create();

    $response = $this->actingAs($admin, 'sanctum')->getJson('/api/users');

    $response->assertOk()->assertJsonFragment([
        'id' => $user->id,
        'orders_count' => 3,
    ]);
});

test('administrator can edit any user', function () {

    $manager = User::factory()->create(['role' => UserRole::MANAGER]);
    $user = User::factory()->create(['role' => UserRole::USER]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin, 'sanctum')->getJson('/api/users');

    $response->assertOk();

    $users = collect($response->json('users'));

    expect($users->firstWhere('id', $user->id)['can_edit'])->toBeTrue();
    expect($users->firstWhere('id', $manager->id)['can_edit'])->toBeTrue();
    expect($users->firstWhere('id', $admin->id)['can_edit'])->toBeTrue();
});

test('manager can only edit users role with the role user', function () {

    $manager = User::factory()->create(['role' => UserRole::MANAGER]);
    $user = User::factory()->create(['role' => UserRole::USER]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($manager, 'sanctum')->getJson('/api/users');

    $response->assertOk();

    $users = collect($response->json('users'));

    expect($users->firstWhere('id', $user->id)['can_edit'])->toBeTrue();

    expect($users->firstWhere('id', $admin->id)['can_edit'])->toBeFalse();
});

test('user can only edit themselves', function () {

    $manager = User::factory()->create(['role' => UserRole::MANAGER]);
    $user = User::factory()->create(['role' => UserRole::USER]);
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/users');

    $response->assertOk();

    $users = collect($response->json('users'));

    expect($users->firstWhere('id', $user->id)['can_edit'])->toBeTrue();
    expect($users->firstWhere('id', $manager->id)['can_edit'])->toBeFalse();
    expect($users->firstWhere('id', $admin->id)['can_edit'])->toBeFalse();
});
