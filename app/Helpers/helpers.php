<?php 
use Carbon\Carbon;
/*****
 * 
 * @function name : csv_to_array 
 */
if(! function_exists('csv_to_array')){
    
    /***
     * 
     * @param1:$Filename  : file containing  data as csv with different columns 
     * @param2: seperator:
     * @return Array ; 
     *   
     */
    function  csv_to_array($filename='', $delimiter=';')
    {
        if(!file_exists($filename) || !is_readable($filename)){
            echo "file is not readable";
           return FALSE;

        }
            

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
    


}