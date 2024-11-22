<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pessoa;

class Licitacao extends Model
{
    use HasFactory;

    // sempre colocar table e primaryKey para listar

    protected $table = 'licitacoes';

    protected $primaryKey = 'id_licitacao';

    protected $fillable = [
        'id_gestor',
        'id_integrante',
        'id_fiscal',
        'objeto_contratacao',
        'sei',
        'sislog',
        'modalidade',
        'situacao', 
        'local',      
        'observacao'  
    ];

    // Relacionamento: uma licitação pertence a um gestor (pessoa)
    public function gestor()
    {
        return $this->belongsTo(Pessoa::class, 'id_gestor');
    }

    // Relacionamento: uma licitação pertence a um integrante (pessoa)
    public function integrante()
    {
        return $this->belongsTo(Pessoa::class, 'id_integrante');
    }

    // Relacionamento: uma licitação pertence a um fiscal (pessoa)
    public function fiscal()
    {
        return $this->belongsTo(Pessoa::class, 'id_fiscal');
    }
}
