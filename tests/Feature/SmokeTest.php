<?php

test('basic test passes', function () {
    expect(true)->toBeTrue();
});

test('can connect to database', function () {
    // Just check if we can query database
    $result = DB::select('SELECT 1 as test');
    expect($result)->not->toBeEmpty();
});
