<?php

    require_once __DIR__.'/Dependencias/vendor/autoload.php';
    use NNV\RestCountries;

    class Request{ 
        
        static function realizarRequest($dato, $criterio){
            $restCountries = new RestCountries;
            $result;
            try{
                switch($criterio){
                    case 1:
                        $result = $restCountries->byRegion($dato);
                        break;
                    case 2:
                        $result = $restCountries->byRegionalBloc($dato);
                        break;
                    case 3:
                        $result = $restCountries->byLanguage($dato);
                        break;
                    case 4:
                        $result = $restCountries->byCapitalCity($dato);
                        break;
                }
            }
            catch(Exception $e){
                return NULL;
            }

            return $result;
        }    
    }