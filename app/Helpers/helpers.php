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

/*****
 * 
 * Get access of an url only through redirected 
 * or you get the access of the url only afte the first .  
 * 
 * @params: $firstUrl: First url of a website 
 * @parm:  $curUrl : This is the current url 
 * @parms: $redirectUrl : This is the url where it should redirect it it does not match 
 * @return: null 
 */
if(! function_exists('is_url_only_after_first')){
     function is_url_only_after_first($firstUrl, $curUrl) {
        if ( !request()->is($curUrl) && url()->previous() !=  url( $firstUrl) )
        {
            // return redirect()->to($redirectUrl); //Send them somewhere else
            return false;
        }
        return true; 
    }
}