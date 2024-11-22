<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pessoa;

class SequenciaLicitacao extends Model
{
    use HasFactory;

    protected $table = 'sequencia_licitacao';
    protected $fillable = ['id_gestor', 'id_integrante','id_fiscal'];

    
}
