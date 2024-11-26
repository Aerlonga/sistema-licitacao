<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


//validar os dados do form
class CadastroLicitacaoRequest extends FormRequest
{
    //Determine se o usuário está autorizado a fazer esta solicitação.*

    public function authorize()
    {
        return true;
    }

    //Obtenha as regras de validação que se aplicam à solicitação.*

    public function rules()
    {                             // validar nulo, tipo, minimo de caractere
        return ['objeto_contratacao' => 'required|string|min:5'];
    }

    /**Obtenha as mensagens de validação personalizadas.* @return array*/

    public function messages()
    {
        return ['objeto_contratacao.required' => 'O campo precisa ser preenchido.', 
        'objeto_contratacao.string' => 'O campo precisa ser diferente de número', 
        'objeto_contratacao.min' => 'Mínimo de 5 caracteres'
        ];
        //procurar mais opçoes de validação para brincar
    }
}

//vai aplicar uma validação em tempo real