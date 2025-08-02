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
                $_message['error_message'] = '
<div style="
    max-width: 600px;
    margin: 30px auto;
    padding: 25px;
    background: #fff;
    border-left: 5px solid #e74c3c;
    border-radius: 4px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    text-align: center;
    font-family: Arial, sans-serif;
">
    <div style="margin-bottom: 20px;">
        <svg style="width: 40px; height: 40px; margin-bottom: 10px;" fill="none" stroke="#e74c3c" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <h3 style="color: #e74c3c; font-size: 22px; margin: 10px 0; font-weight: 600;">
            Voting Limit Exceeded
        </h3>
    </div>

    <div style="color: #333; line-height: 1.6; margin-bottom: 20px;">
        <p style="margin-bottom: 15px;">
            There are already more than '.$max_use_clientIP.' votes cast from your IP address:
            <br>
            <span style="font-weight: 700; color: #000; background: #f5f5f5; padding: 3px 8px; border-radius: 3px; display: inline-block; margin: 8px 0;">
                '.$clientIP.'
            </span>
            <br>
            We are sorry to say that you cannot vote any more using this IP address.
        </p>
        
        <p style="
            margin: 20px 0;
            padding: 12px;
            background: #f9f9f9;
            border-radius: 4px;
            font-size: 15px;
            color: #555;
        ">
            तपाइको आइपी एड्रेस <strong>'.$clientIP.'</strong> वाट पहिले नै '.$max_use_clientIP.' पटक भोट हाली सकिएको छ। 
            माफ गर्नु होला, हाम्राे नियम अनुसार एउटा आइपि एड्रेस वाट '.$max_use_clientIP.' भन्दा वढी भोट हाल्न मिल्दैन।
        </p>
    </div>

    <div style="margin-top: 25px;">
        <a href="'.route('dashboard').'" style="
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
        " onmouseover="this.style.background=\'#2980b9\';this.style.transform=\'translateY(-2px)\'" 
        onmouseout="this.style.background=\'#3498db\';this.style.transform=\'translateY(0)\'">
            <svg style="width: 16px; height: 16px; vertical-align: middle; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Go to Dashboard | ड्यासबोर्डमा जानुहोस्
        </a>
    </div>
</div>';

$_message['return_to'] = '404';

            }
            return $_message;
        }

    }


