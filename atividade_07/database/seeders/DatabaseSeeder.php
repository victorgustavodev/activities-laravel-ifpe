<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    
    public function run(){
    $this->call([
        CategorySeeder::class,
        AuthorPublisherBookSeeder::class,
        UserBorrowingSeeder::class, // Novo seeder adicionado aqui
    ]);

    User::create([
        'name' => 'Admin',
        'email' => 'adm@adm.com',
        'password' => bcrypt('adm@adm.com'),
        'role' => 'admin',
    ]);
    }
}