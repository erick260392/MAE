<?php

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

arch('models')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->not->toBeAbstract();

arch('livewire')
    ->expect('App\Livewire')
    ->toExtend('Livewire\Component');

arch('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

test('models define mass assignment protection', function () {
    $modelClasses = collect(File::files(app_path('Models')))
        ->map(fn ($file) => 'App\\Models\\'.$file->getFilenameWithoutExtension())
        ->filter(fn (string $class) => is_subclass_of($class, Model::class));

    expect($modelClasses)->not->toBeEmpty();

    $modelClasses->each(function (string $class): void {
        $reflection = new ReflectionClass($class);

        $hasProtectionProperty = $reflection->hasProperty('fillable') || $reflection->hasProperty('guarded');
        $hasProtectionAttribute = collect($reflection->getAttributes())
            ->contains(fn (ReflectionAttribute $attribute) => in_array($attribute->getName(), [Fillable::class, Guarded::class], true));

        expect($hasProtectionProperty || $hasProtectionAttribute)
            ->toBeTrue("Failed asserting that [{$class}] defines fillable or guarded protection.");
    });
});
