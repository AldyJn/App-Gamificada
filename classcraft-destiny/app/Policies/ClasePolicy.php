<?php
// app/Policies/ClasePolicy.php

namespace App\Policies;

use App\Models\{Usuario, Clase};

class ClasePolicy
{
    public function view(Usuario $usuario, Clase $clase): bool
    {
        // Profesor propietario o estudiante inscrito
        return $usuario->esDocente() && $clase->id_docente === $usuario->docente->id ||
               $usuario->esEstudiante() && $clase->estudiantes()
                   ->where('estudiante.id', $usuario->estudiante->id)
                   ->exists();
    }

    public function update(Usuario $usuario, Clase $clase): bool
    {
        return $usuario->esDocente() && $clase->id_docente === $usuario->docente->id;
    }

    public function delete(Usuario $usuario, Clase $clase): bool
    {
        return $usuario->esDocente() && $clase->id_docente === $usuario->docente->id;
    }
}