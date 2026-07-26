<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserForm;
use App\Livewire\Intermediaries\IntermediaryIndex;
use App\Livewire\Intermediaries\IntermediaryForm;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductForm;
use App\Livewire\Policies\PolicyIndex;
use App\Livewire\Policies\PolicyForm;
use App\Livewire\Numerators\NumeratorIndex;
use App\Livewire\Numerators\NumeratorForm;
use App\Livewire\Dictionaries\DictionaryIndex;
use App\Livewire\Dictionaries\DictionaryForm;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Users\UserProfile;
use App\Livewire\ProductTypes\ProductTypeIndex;
use App\Livewire\ProductTypes\ProductTypeForm;
use App\Livewire\Banks\BankIndex;
use App\Livewire\Banks\BankForm;
use App\Livewire\Promocodes\PromocodeIndex;
use App\Livewire\Promocodes\PromocodeForm;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Profile
    Route::get('/profile', UserProfile::class)->name('profile.show');
    Route::get('/profile/{id}', UserProfile::class)->name('profile.user');

    Route::middleware('can:users.view')->group(function () {
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{id}', UserForm::class)->name('users.edit');
    });
    Route::middleware('can:intermediaries.view')->group(function () {
        Route::get('/intermediaries', IntermediaryIndex::class)->name('intermediaries.index');
        Route::get('/intermediaries/create', IntermediaryForm::class)->name('intermediaries.create');
        Route::get('/intermediaries/{id}', IntermediaryForm::class)->name('intermediaries.edit');
    });
    Route::middleware('can:products.view')->group(function () {
        Route::get('/products', ProductIndex::class)->name('products.index');
        Route::get('/products/create', ProductForm::class)->name('products.create');
        Route::get('/products/{id}', ProductForm::class)->name('products.edit');
    });
    Route::middleware('can:policies.view')->group(function () {
        Route::get('/policies', PolicyIndex::class)->name('policies.index');
        Route::get('/policies/create', PolicyForm::class)->name('policies.create');
        Route::get('/policies/{id}', PolicyForm::class)->name('policies.edit');
    });
    // Numerators
    Route::middleware('can:numerators.view')->group(function () {
        Route::get('/numerators', NumeratorIndex::class)->name('numerators.index');
        Route::get('/numerators/create', NumeratorForm::class)->name('numerators.create')->middleware('can:numerators.manage');
        Route::get('/numerators/{id}', NumeratorForm::class)->name('numerators.edit')->middleware('can:numerators.view');
    });
    // Dictionaries
    Route::middleware('can:dictionaries.view')->group(function () {
        Route::get('/dictionaries', DictionaryIndex::class)->name('dictionaries.index');
        Route::get('/dictionaries/create', DictionaryForm::class)->name('dictionaries.create')->middleware('can:dictionaries.manage');
        Route::get('/dictionaries/{id}', DictionaryForm::class)->name('dictionaries.edit');
    });
    // Roles
    Route::middleware('can:roles.view')->group(function () {
        Route::get('/roles', RoleIndex::class)->name('roles.index');
        Route::get('/roles/create', RoleForm::class)->name('roles.create')->middleware('can:roles.manage');
        Route::get('/roles/{id}', RoleForm::class)->name('roles.edit');
    });

    // Product Types
    Route::middleware('can:products.view')->group(function () {
        Route::get('/product-types', ProductTypeIndex::class)->name('product-types.index');
        Route::get('/product-types/create', ProductTypeForm::class)->name('product-types.create')->middleware('can:products.manage');
        Route::get('/product-types/{id}', ProductTypeForm::class)->name('product-types.edit');
    });
    // Banks
    Route::middleware('can:products.view')->group(function () {
        Route::get('/banks', BankIndex::class)->name('banks.index');
        Route::get('/banks/create', BankForm::class)->name('banks.create')->middleware('can:products.manage');
        Route::get('/banks/{id}', BankForm::class)->name('banks.edit');
    });
    // Promocodes
    Route::middleware('can:products.view')->group(function () {
        Route::get('/promocodes', PromocodeIndex::class)->name('promocodes.index');
        Route::get('/promocodes/create', PromocodeForm::class)->name('promocodes.create')->middleware('can:products.manage');
        Route::get('/promocodes/{id}', PromocodeForm::class)->name('promocodes.edit');
    });
});

require __DIR__ . '/auth.php';
