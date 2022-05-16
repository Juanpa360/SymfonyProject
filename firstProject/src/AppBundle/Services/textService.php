<?php

namespace AppBundle\Services;

class textService
{
    public function textUpper($tapas)
    {
        $nuevas_tapas = [];
        for ($i=0; $i < count($tapas); $i++) { 
            $nuevas_tapas[$i] = clone $tapas[$i];
            $nuevas_tapas[$i]->setTitulo(strtoupper($tapas[$i]->getTitulo()));
        }
        return $nuevas_tapas;

    }

    public function textLower($tapas)
    {
        $nuevas_tapas = [];
        for ($i=0; $i < count($tapas); $i++) { 
            $nuevas_tapas[$i] = clone $tapas[$i];
            $nuevas_tapas[$i]->setDescripcion(strtolower($tapas[$i]->getDescripcion()));
        }
        return $nuevas_tapas;
    }
}
?>