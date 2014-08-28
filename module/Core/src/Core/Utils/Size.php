<?php
namespace Core\Utils;

/**
 * Classe que retorna um tamanho de dados, que possa ser lido por humanos
 */
class Size 
{
	public function getReadableSize($value)
	{
		if ($value < 1024) 
            return  $mem_usage." bytes"; 
        elseif ($value < 1048576) 
            return round($value/1024,2)." kilobytes"; 
        else 
            return round($value/1048576,2)." megabytes"; 
	}
}