<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Book::create([
            'bookName' => 'Advan',
            'bookCode' => 'AW12398',
            'bookAuthor' => 'Laravel',
        ]);
    }
}
