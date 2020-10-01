<?php

/* --------------------------------------------------
 * Common Helper functions 
 * Don't put any query related method here
 * --------------------------------------------------
 */
global $preCss, $is_backtrace;
global $prof_timing, $prof_names;$printOn;

$preCss = "style='border:1px solid #CCC;background-color:#EEE;padding:5px;margin-top:50px;'";
$is_backtrace = true;
$printOn = true;

/*
 * Formated print_r
 * Usage p($data,$data2,$data3);
 */

function p() {
    global $preCss;
    global $is_backtrace;
    global $printOn;
    $backtrace_txt = '';

    if(!$printOn){
        return "";
    }

    $op = '<div ' . $preCss . '>';
    if ($is_backtrace) {
        $bt = debug_backtrace();

        foreach ($bt as $key => $btrace) {
            if (!in_array($btrace["function"], ["p"]) && strpos($btrace["file"], "Functions.php") == false) {
                $debugArray = $btrace;
                break;
            }
        }
        $backtrace_txt = '<div style="background-color:#DDD;padding:3px;maring-top:-2px;"><b>' . $debugArray['file'] . '</b>: <b>' . $debugArray['line'] . '</b></div><br/>';
    }
    $op .= $backtrace_txt;
    $args = func_get_args();
    foreach ($args as $k => $arg) {
        $op .= "<pre style='border: 1px dotted;padding:10px;background-color:#FFF;'>";
        if (is_array($arg) || is_object($arg)) {
            $op .= print_r($arg, true);
        } else {
            $op .= $arg;
        }
        $op .= "</pre><br />";
    }
    $op .= '</div><br />';

    // if($printOn){
        echo $op;
    // }
}

function off_backtrace() {
    global $is_backtrace;
    $is_backtrace = false;
}

// Only print
function pr() {
    $args = func_get_args();
    call_user_func_array('p', $args);
}

// RED Only print
function hpr() {
    echo "<div style='background-color:yellow;'>";
    $args = func_get_args();
    call_user_func_array('p', $args);
    echo "</div>";
}

// Print + exit
function pe() {
    $args = func_get_args();
    call_user_func_array('p', $args);
    exit();
}

/* @Desc Use Only for debug : echo + exit  e.g. ee("hello",1,array("My Value","Test Value"));
 * @params : string and array both allowed
 */

function ee() {
    $args = func_get_args();
    foreach ($args as $k => $arg) {
        if (is_array($arg) || is_object($arg)) {
            echo '<pre>';
            print_r($arg);
            echo '</pre>';
        } else {
            echo $arg;
        }
    }
    exit;
}

/* @Desc : Print anying no of times : e.g printNtimes('<br/>',10); */

function printNtimes($printValue, $no = 1) {
    for ($i = 1; $i <= $no; $i++) {
        echo $printValue;
    }
}

/*
 * @Var Dumper multiple e.g. vd($arr1,$arr2,$var1,$var2) and exit
 */

function vd() {
    global $preCss;
    global $is_backtrace;
    $backtrace_txt = '';

    echo '<div ' . $preCss . '>';
    if ($is_backtrace) {
        $bt = debug_backtrace();
        $debugArray = $bt[0];
        if (!isset($debugArray['file'])) {
            $debugArray = $bt[2];
        }
        $backtrace_txt = '<b>' . $debugArray['file'] . '</b>: <b>' . $debugArray['line'] . '</b><br/><br/>';
    }
    echo $backtrace_txt;
    $args = func_get_args();
    foreach ($args as $k => $arg) {
        if ($arg == 'exit') {
            exit;
        }
        echo "<pre style='border: 1px solid #ccc;padding:10px;'>";
        var_dump($arg);
        echo "</pre><br />";
    }
    echo '</div><br />';
}

/*
 * @Desc : String Function :: Camelcase string
 *  str_titlecase("test word") => Test Word
 *  str_titlecase("test word(someting)",["("]) => Test Word(Someting) 
 */

function str_titlecase($string, $cases = []) {

    if (isset($cases) && !empty($cases)) {

        foreach ($cases as $case) {

            $string_arr = explode($case, $string);
            if (isset($string_arr) && !empty($string_arr)) {

                $string_arr = array_map("ucfirst", array_map("strtolower", $string_arr));
                $string = implode($case, $string_arr);
            }
        }
        return ucwords($string);
    }
    return ucwords(strtolower($string));
}

/*
 * @Desc : Array Function : Change array values to title case 
 * @param : $array = [1=>"hello world",4=>'thank you'];
 * @return : $array = [1=>"Hello World",4=>'Thank You'];
 */

function array_values_titlecase($array) {
    return array_map("ucwords", array_map('strtolower', $array));
}

function getP($exit = false) {
    if (isset($_GET) && !empty($_GET)) {
        pr($_GET);
        if ($exit)
            exit;
    }
}

function postP($exit = false) {
    if (isset($_POST) && !empty($_POST)) {
        pr($_POST);
        if ($exit)
            exit;
    }
}

/**
 * @Desc : Array Function - check string start with given needle or not 
 * @Return : boolean
 * $str = '|apples}';
 * 	echo str_endsWith($str, '}'); //Returns true
 */
function str_startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * @Desc : Array Function - check string end with given needle or not 
 * $str = '|apples}';
 * 	echo str_endsWith($str, '}'); //Returns true
 * @Return : boolean
 */
function str_endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

function getYesNo($value) {
    return $value ? 'Yes' : 'No';
}

function xmlToArray($xml) {
    $ob = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $json = json_encode($ob);
    $configData = json_decode($json, true);
    return $configData;
}

function pXML($xml) {
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-type: text/xml");
    header("Content-Description: PHP Generated Data");
    exit($xml);
}

/* PHP Error Reporting On */

function phpError() {
    ini_set('display_errors', 'On');
    error_reporting(E_All);
}

// Debug PHP Error from your end : add ?phpEr=true
function debugPhpError() {
    if (isset($_GET["phpEr"]) && $_GET["phpEr"] === true) {
        phpError();
    }
}

debugPhpError();

/* Json Pretty Print
 * $arr : Pass Array or Json
 */

function jsonP($arr, $exit = true) {
    global $preCss;
    global $is_backtrace;

    $op = "<div " . $preCss . ">";
    if ($is_backtrace) {
        $bt = debug_backtrace();
        foreach ($bt as $key => $btrace) {
            if (!in_array($btrace["function"], ["p"])) {
                $debugArray = $btrace;
                break;
            }
        }
        $op .= '<div style="background-color:#DDD;padding:3px;margin-top:1px;"><b>' . $debugArray['file'] . '</b>: <b>' . $debugArray['line'] . '</b></div>';
    }

    $op .= "<pre style='border: 1px dotted;padding:10px;background-color:#FFF;'>";
    if (is_array($arr)) {
        $op .= json_encode($arr, JSON_PRETTY_PRINT);
    } else {
        $op .= json_encode(json_decode($arr), JSON_PRETTY_PRINT);
    }
    $op .= "</pre><br/>";

    $op .= "</div>";
    echo $op;
    if ($exit)
        exit();
}

/* @Desc: For Debuging Print. It will show only if you pass debug=1 in query string */

function dpr() {
    if (isset($_GET["debug"]) && $_GET["debug"] == 1) {
        $args = func_get_args();
        call_user_func_array('p', $args);
    }
}

/* @Desc: For Debuging Print. It will show only if you pass debug=1 in query string */

function dpe() {
    if (isset($_GET["debug"]) && $_GET["debug"] == 1) {
        $args = func_get_args();
        call_user_func_array('p', $args);
        exit();
    }
}

/*
  Yii:: Query and Data Print
  @$qry: object of query
 */

function qpr($qry, $data = '') {
    $data ? pr($qry->createCommand()->rawSql, $data) : pr($qry->createCommand()->rawSql);
}

/* Yii:: Query and Data Print + exit
 *  @$qry: object of query 
 */

function qpe($qry, $data = '') {
    $data ? pe($qry->createCommand()->rawSql, $data) : pe($qry->createCommand()->rawSql);
}

function code($code) {
    return "<pre><xmp>$code</xmp></pre>";
    return "<pre>" . str_replace(">", "&gt;", str_replace("<", "&lt;", $code)) . "</pre>";
}

function string_after_number($str) {
    $ln = strlen($str);
    for ($i = 0; $i < $ln; $i++) {
        if (is_numeric($str[$i]))
            break;
    }
    return substr($str, $i, $ln);
}

function jsonPretty($data,$isDebug=false) {
	$actualData = $data;
	$data = !is_array($data) ? json_decode($data,true) : $data;	
	$data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	
	if($data == "null"){	
		$data = $actualData;
	}
	return "<pre style='border:0;background-color:transparent;'>" . $data . "</pre>";
}

function get_string_between($string, $start = "{", $end = "}") {
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) {
        return '';
    }

    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function findVariables($template, $findStart = "{", $findEnd = "}", $placeHolderStart = '<', $placeHolderEnd = '>') {

    $cli_config_arr = explode("\n", $template);
    $variables = [
        "replacements" => [],
        "master" => [],
    ];
    foreach ($cli_config_arr as $key => $line) {
        $variable = get_string_between($line, $findStart, $findEnd);
        if (!empty($variable) && strpos($variable, ":") === false) {
            $variables["replacements"]["{" . $variable . "}"] = '"' . $placeHolderStart . $variable . $placeHolderEnd . '"';
            $variables["master"][] = $variable;
        }
    }
    $variables["master"] = array_unique($variables["master"]);
    $variables["replacements"] = array_unique($variables["replacements"]);
    return $variables;
}

/*
 * @DESCCheck Given data is valid json or not 
 * @return : true|false
 */

function is_json($data = NULL) {
    if (!empty($data)) {
        @json_decode($data);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}


function qos($bandwidth, $profile, $data) {
    
    if ($profile == 11) {
        if (array_sum($data) > 100) {
            return array('status' => false, 'error' => "Aggregate value should not be 100");
        }
        if (!is_int($data['platinum']) || !is_int($data['gold']) || !is_int($data['silver']) || !is_int($data['bronze'])) {
            return array('status' => false, 'error' => "Class of service value is not correct format");
        }
        $data['bronze'] = 100-($data['platinum']+$data['gold']+$data['silver']);
    }
    
    $qos['platinum'] = ($bandwidth * $data['platinum']) / 100;
    $qos['gold'] = ($bandwidth * $data['gold']) / 100;
    $qos['silver'] = ($bandwidth * $data['silver']) / 100;
    $qos['bronze'] = ($bandwidth * $data['bronze']) / 100;
    $qos['police_bc'] =  ($qos['platinum'] * 1.5)/8;
    
    $kqos['platinum'] = $qos['platinum'] * 1024;
    $kqos['gold'] =  $qos['gold'] * 1024;
    $kqos['silver'] =  $qos['silver'] * 1024;
    $kqos['bronze'] =  $qos['bronze'] * 1024;
    
    $kqos['police_bc'] =  ($kqos['platinum'] * 1.5)/8;
    
    return array('mbps'=>$qos,'kbps'=>$kqos);
}

/*
 * @Desc : Insert array at specific position in given array
 * @param : $array = [
 *    "key1"=>"value1", 
 *    "key2"=>"value2", 
 *    "key3"=>"value3",
 *    "key4"=>"value4",  
 * ];
 *  @param : $insertArray = ["transaction-id"=>100];
 *  @param : $position = 3
 * @response : 
 * $array = [
 *    "key1"=>"value1", 
 *    "key2"=>"value2",
 *    "transaction-id"=>100 
 *    "key3"=>"value3",
 *    "key4"=>"value4",  
 * ];
 */
function insertArrayAtPosition($array,$insertArray,$position){
    $i = 1;
    $isAdded = false;
    $newBlockArray =[];
    foreach($array as $key=>$value){                    
        if($i == $position){
            $isAdded = true;
            foreach($insertArray as $key1=>$value1){
                $newBlockArray[$key1] = $value1;                
            }
        }
        $i++;
        $newBlockArray[$key] = $value;
    }
    if(!$isAdded && $i==$position){
        foreach($insertArray as $key1=>$value1){
                $newBlockArray[$key1] = $value1;                
        }
    }
    return $newBlockArray;
}

// Call this at each point of interest, passing a descriptive string
function prof_flag($str)
{
    global $prof_timing, $prof_names;
    $prof_timing[] = microtime(true);
    $prof_names[] = $str;
}

// Call this when you're done and want to see the results
function prof_print()
{
    global $prof_timing, $prof_names;
    $size = count($prof_timing);
    for($i=0;$i<$size - 1; $i++)
    {
        echo "<b>{$prof_names[$i]}</b><br>";
        echo sprintf("&nbsp;&nbsp;&nbsp;%f<br>", $prof_timing[$i+1]-$prof_timing[$i]);
    }
    echo "<b>{$prof_names[$size-1]}</b><br>";
}


/*@Desc : Get Value between tags
 *@param : input string 
 *@$tagname : tag name from between to get value
 *@ e.g <code>hello world</code> Good Morning
 *@return : hello world
 */
function getTagValue($string, $tagname)
{
    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
    preg_match($pattern, $string, $matches);
    return isset($matches[1])?$matches[1]:false;
}



function getTagValueAll($string, $tagname)
{
    $pattern = "#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s";
    preg_match_all($pattern, $string, $matches);
    return $matches;       
}


function setYiiErrors($errors,$implode=false){
	$ret = [];
	if(!$errors)return $errors;
	foreach($errors as $key=>$value){
		if(is_array($value)){
			foreach($value as $err){
				$ret[] = $err;
			}
		}
	}

	return $implode ? implode(",",$ret) :  $ret;	
}


function dateDiffMicro($date1, $date2){

    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);

    //Absolute val of Date 1 in seconds from  (EPOCH Time) - Date 2 in seconds from (EPOCH Time)
    $diff = abs(strtotime($date1->format('d-m-Y H:i:s.u'))-strtotime($date2->format('d-m-Y H:i:s.u')));

    //Creates variables for the microseconds of date1 and date2
    $micro1 = $date1->format("u");
    $micro2 = $date2->format("u");

    //Absolute difference between these micro seconds:
    $micro = abs($micro1 - $micro2);

    //Creates the variable that will hold the seconds (?):
    $difference = $diff.".".$micro;

    return $difference;
}

/*@Desc : recursing mulitdimension array implode
 *@param array : $array - multidimension array
 *@param string : $glue - comma(,)
 *@return string : imploded string
 */
function multi_implode($array, $glue) {
            $ret = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $ret .= multi_implode($item, $glue) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }

        $ret = substr($ret, 0, 0-strlen($glue));

        return $ret;
}


function convert_BPS_TO_KB($input,$isRound=true){
    $ret = $input * 0.001;
    return $isRound ? round($ret) : $ret;
}

function convert_GB_TO_KB($input,$isRound=true){
    $ret =  $input * 1024 * 1024;
    return $isRound ? round($ret) : $ret;
}

function convert_MB_TO_KB($input,$isRound=true){
    $ret =  $input * 1024;
    return $isRound ? round($ret) : $ret;
}

function convert_BITS_TO_KB($input,$isRound=true){
    $ret =  $input/8000;
    return $isRound ? round($ret) : $ret;
}


function array_flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

