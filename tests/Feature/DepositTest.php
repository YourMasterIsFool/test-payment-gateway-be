<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;

uses(DatabaseTransactions::class);
it('success create deposit', function() {
    $generateUuid = Uuid::uuid4()->toString();
    $mockupData = [
        'amount' => 10000,
        'order_id' => $generateUuid,
        'timestamp' => now()->toISOString(),
    ];

    $response = $this->post('/deposit', $mockupData);
    $response->assertStatus(200);
});

it("must be error required", function() {
    $response = $this->post('deposit'); 
    $response->assertStatus(422);
    $response
    ->assertJsonValidationErrors(['amount', 'order_id', 'timestamp']) ;
});
