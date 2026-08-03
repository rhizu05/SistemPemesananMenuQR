<?php

use App\Models\User;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a five-minute expiry when sending an OTP', function () {
    $this->mock(FonnteService::class)->shouldReceive('sendWhatsAppOTP')->andReturn(true);

    $response = $this->post('/login/send-otp', ['phone' => '8123456789']);

    $response->assertSessionHas('otp_expires_at');
    $response->assertSessionHas('otp_phone', '628123456789');

    $expiresAt = Carbon::parse(session('otp_expires_at'));
    expect($expiresAt->gt(now()))->toBeTrue();
    expect(abs($expiresAt->diffInMinutes(now())))->toBeGreaterThan(4);
});

it('blocks resending an OTP within five minutes', function () {
    $this->mock(FonnteService::class)->shouldReceive('sendWhatsAppOTP')->times(1)->andReturn(true);

    $this->post('/login/send-otp', ['phone' => '8123456789']);

    $response = $this->post('/login/send-otp', ['phone' => '8123456789']);

    $response->assertSessionHasErrors('phone');
});

it('rejects an incorrect OTP', function () {
    session([
        'otp_code' => '123456',
        'otp_phone' => '628123456789',
        'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString(),
        'otp_sent' => true,
    ]);

    $response = $this->post('/login/verify-otp', ['otp' => '999999']);

    $response->assertSessionHasErrors('otp');
});

it('rejects an expired OTP', function () {
    session([
        'otp_code' => '123456',
        'otp_phone' => '628123456789',
        'otp_expires_at' => Carbon::now()->subMinute()->toDateTimeString(),
        'otp_sent' => true,
    ]);

    $response = $this->post('/login/verify-otp', ['otp' => '123456']);

    $response->assertSessionHasErrors('otp');
});

it('logs in a customer with a valid OTP', function () {
    session([
        'otp_code' => '123456',
        'otp_phone' => '628123456789',
        'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString(),
        'otp_sent' => true,
    ]);

    $response = $this->post('/login/verify-otp', ['otp' => '123456']);

    $response->assertRedirect(route('customer.menu'));
    $this->assertAuthenticated();
    expect(User::where('phone', '628123456789')->exists())->toBeTrue();
});
