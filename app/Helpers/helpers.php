<?php 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

/***
 * 
 * Get random string of any size 
 * @param $nchar: no of characters that is to be defined
 * @return : String  
 */
   if(! function_exists('get_random_string')){
       function get_random_string ($nChar){
        $random = substr(md5(mt_rand()), 0, $nChar-1);
        $random = rand(1,9).strtoupper($random);
        return $random ;
       }
   }

   /***
    * 
    *Check Ip Address 
    * @params1 : string : ip address 
    *@params2:  Number :maximum no of ip address used.
    *@return : array of string of  size 2. 
    * with error_message and return to
    */
    if(! function_exists('check_ip_address')){
        function check_ip_address($clientIP, $max_use_clientIP){
        $_message                   =[];
        $_message['error_message']  =""; 
        $_message['return_to']      = "";
        $ip_condition               =  "client_ip ='". $clientIP."' ";
        $ip_condition               .=  " AND has_voted"; 
        // dd($ip_condition);
            
        
        $select_statement           = "count(case when ";
        $select_statement           .= $ip_condition." ";
        $select_statement           .= " then 1 end) as ipCount";
        // dd( $select_statement); 
        $times_ip_used              = DB::table('codes')
                                    ->selectRaw($select_statement)
                                    ->get();
        // dd($times_ip_used);
        // dd(max_use_clientIP);
        $times_use_cleintIP = $times_ip_used[0]->ipCount;
        // if($times_use_cleintIP>$max_use_clientIP){
        if($times_use_cleintIP >=$max_use_clientIP){
            $_message['error_message'] ='<div style="margin:auto; color:red; 
                padding:20px; font-weight:bold; text-align:center;">';
                $_message['error_message'] .="<p> There are alerady more than " ;
                $_message['error_message'] .=$max_use_clientIP ;
                $_message['error_message'] .=" Votes casted from your ip address: "; 
                $_message['error_message'] .='<br> <span style="font-weight:bold; color: black;"> '
                .$clientIP."</span><br>";
                $_message['error_message'] .="We are sorry to say that You can not vote 
                any more using this ip address.</p>";
                $_message['error_message'] .=" 
                 <p> तपाइको आइपी एड्रेस ".$clientIP. " वाट पहिले नै "
                 .$max_use_clientIP.
                 ' पटक भाेट हाली  सकिएको छ। माफ गर्नु होला,  हाम्राे नियम अनुसार एउटा आइपि एड्रेस वाट '
                 .$max_use_clientIP.
                 ' भन्दा वढी भोट हाल्न मिल्दैन।  </p>
                 
                 <p style="margin-top: 4px; color:#1E90FF; font-weight:bold;">  
                    <a href="'.route('dashboard'). '"> Go to the Dashboard | ड्यास वोर्डमा जानलाइ यहाँ क्लिक गर्नुहोस।</a> </p> 
                    </div>
                 ';
            $_message['return_to'] ='404';
            // dd($_message);
           
          }
        return $_message;        
    }
}