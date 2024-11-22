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
            ['nome' => 'Vitor', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['nome' => 'Thaynara', 'created_at' => new DateTime(), 'updated_at' => new DateTime()],
            ['nome' => 'Francielle', 'created_at' => new DateTime(), 'updated_at' => new DateTime()]
        ];
        foreach ($pessoas as $pessoa) {
            Pessoa::create($pessoa);
        }
    }
}
