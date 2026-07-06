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

Route::get('/', fn()=>redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn()=>view('dashboard'))->name('dashboard');

    Route::middleware('can:users.view')->group(function(){
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{id}', UserForm::class)->name('users.edit');
    });
    Route::middleware('can:intermediaries.view')->group(function(){
        Route::get('/intermediaries', IntermediaryIndex::class)->name('intermediaries.index');
        Route::get('/intermediaries/create', IntermediaryForm::class)->name('intermediaries.create');
        Route::get('/intermediaries/{id}', IntermediaryForm::class)->name('intermediaries.edit');
    });
    Route::middleware('can:products.view')->group(function(){
        Route::get('/products', ProductIndex::class)->name('products.index');
        Route::get('/products/create', ProductForm::class)->name('products.create');
        Route::get('/products/{id}', ProductForm::class)->name('products.edit');
    });
    Route::middleware('can:policies.view')->group(function(){
        Route::get('/policies', PolicyIndex::class)->name('policies.index');
        Route::get('/policies/create', PolicyForm::class)->name('policies.create');
        Route::get('/policies/{id}', PolicyForm::class)->name('policies.edit');
    });
    // Нумераторы
    Route::middleware('can:numerators.view')->group(function(){
        Route::get('/numerators', NumeratorIndex::class)->name('numerators.index');
        Route::get('/numerators/create', NumeratorForm::class)->name('numerators.create')->middleware('can:numerators.manage');
        Route::get('/numerators/{id}', NumeratorForm::class)->name('numerators.edit')->middleware('can:numerators.view');
    });
    // Словари
    Route::middleware('can:dictionaries.view')->group(function(){
        Route::get('/dictionaries', DictionaryIndex::class)->name('dictionaries.index');
        Route::get('/dictionaries/create', DictionaryForm::class)->name('dictionaries.create')->middleware('can:dictionaries.manage');
        Route::get('/dictionaries/{id}', DictionaryForm::class)->name('dictionaries.edit');
    });
    // Роли
    Route::middleware('can:roles.view')->group(function(){
        Route::get('/roles', RoleIndex::class)->name('roles.index');
        Route::get('/roles/create', RoleForm::class)->name('roles.create')->middleware('can:roles.manage');
        Route::get('/roles/{id}', RoleForm::class)->name('roles.edit');
    });
});

require __DIR__.'/auth.php';
