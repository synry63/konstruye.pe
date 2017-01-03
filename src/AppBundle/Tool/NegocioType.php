<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 8/17/16
 * Time: 3:11 PM
 */
namespace AppBundle\Tool;

class NegocioType {

    public function getArrayAcordingToTypeOf($n)
    {
        $arr_out = ['inmueble' => false, 'constructura-inmobiliaria' => false, 'especialista' => false, 'proveedor' => false];
        if ($n instanceof Inmueble) {
            $arr_out['inmueble'] = true;
        } else if ($n instanceof ConstructoraInmobiliaria) {
            $arr_out['constructura-inmobiliaria'] = true;
        } else if ($n instanceof Proveedor) {
            $arr_out['proveedor'] = true;
        } else if ($n instanceof Especialista) {
            $arr_out['especialista'] = true;
        }

        return $arr_out;
    }
}