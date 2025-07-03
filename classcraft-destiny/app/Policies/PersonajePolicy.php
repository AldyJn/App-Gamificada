<?php
// app/Policies/PersonajePolicy.php

namespace App\Policies;

use App\Models\{Usuario, Personaje};

class PersonajePolicy
{
    public function view(Usuario $usuario, Personaje $personaje): bool
    {
        // Propietario del personaje o profesor de la clase
        return $usuario->esEstudiante() && $personaje->id_estudiante === $usuario->estudiante->id ||
               $usuario->esDocente() && $personaje->clase->id_docente === $usuario->docente->id;
    }

    public function update(Usuario $usuario, Personaje $personaje): bool
    {
        return $usuario->esEstudiante() && $personaje->id_estudiante === $usuario->estudiante->id;
    }
}