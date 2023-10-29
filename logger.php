<?
function logger($data, $json=true, $fileName = "simpo_log.txt"){
    if($json)
       $data = json_encode($data);
   $fileLog = $data . PHP_EOL;
   $filename = $_SERVER['DOCUMENT_ROOT'] . '/' . $fileName;
   if (is_writable($filename)){
       $handle = fopen($filename, 'a');
       fwrite($handle, $fileLog);
       fclose($handle);
   }
}