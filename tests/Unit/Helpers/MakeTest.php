<?php

declare(strict_types=1);

use App\Helpers\Make;
use Illuminate\Support\Str;

it('creates key from string')
    ->expect(fn (): string => Make::makeKey('example-key'))
    ->toBe('example-key');

it('creates key from array')
    ->expect(fn (): string => Make::makeKey(['example', 'key']))
    ->toBe('example-key');

it('hashes string when exceeding max length')
    ->expect(fn (): string => Make::makeKey(str_repeat('a', 65)))
    ->toBe(Str::slug(hash('sha256', str_repeat('a', 65))));

it('hashes array when combined length exceeds max')
    ->expect(fn (): string => Make::makeKey([str_repeat('a', 33), str_repeat('b', 33)]))
    ->toBe(Str::slug(hash('sha256', str_repeat('a', 33).'-'.str_repeat('b', 33))));

it('handles special characters')
    ->expect(fn (): string => Make::makeKey('Hello World! @#$%'))
    ->toBe('hello-world-at');

it('handles empty string')
    ->expect(fn (): string => Make::makeKey(''))
    ->toBe('');

it('handles empty array')
    ->expect(fn (): string => Make::makeKey([]))
    ->toBe('');

it('handles array with empty strings')
    ->expect(fn (): string => Make::makeKey(['', '']))
    ->toBe('');
