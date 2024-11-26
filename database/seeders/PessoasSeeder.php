<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pessoa;
use DateTime;

class PessoasSeeder extends Seeder
{
    public function run()
    {
        $pessoas = [
            ['nome' => 'Joao', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['nome' => 'Maria', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['nome' => 'Pedro', 'created_at' => new DateTime(), 'updated_at' => new DateTime()]
        ];
        foreach ($pessoas as $pessoa) {
            Pessoa::create($pessoa);
        }
    }
}
