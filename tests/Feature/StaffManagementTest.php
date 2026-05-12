<?php

use App\Models\User;

it('shows and creates staff members', function () {
    $staff = User::factory()->create([
        'name' => 'Loan Officer',
        'address' => 'Plot 1 Main Street',
        'email' => 'officer@example.com',
        'tel_no' => '0777000001',
        'role' => 'loan_supervisor',
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($staff, 'staff')
        ->get(route('staff.index'))
        ->assertOk()
        ->assertSee('Staff List');

    $this->actingAs($staff, 'staff')
        ->post(route('staff.store'), [
            'name' => 'New Supervisor',
            'address' => 'Kampala Road',
            'email' => 'supervisor@example.com',
            'tel_no' => '0777000002',
            'role' => 'loan_supervisor',
        ])
        ->assertRedirect(route('staff.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'supervisor@example.com',
        'role' => 'loan_supervisor',
        'business_name' => null,
    ]);
});