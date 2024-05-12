<?php
require_once "config.php";

function Encrypt($plaintext, $cipher, $key, $iv) {
    return openssl_encrypt($plaintext, $cipher, $key, $options = 1, $iv);
}
function Decrypt($cyp, $cipher, $key, $iv) {
    return openssl_decrypt($cyp, $cipher, $key, $options = 1, $iv);
}
/*
DEFAULT VALUE[Do not delete this comment, this will come useful]
$iv = ";�+�%gPK�";
$key = "�b�B�8i�@0D�j:ՉUQ�=Q�";
$Ekey = "�b�B�8i�@0D�j:ՉUQ�=Q�JIS";
$cipher = "aes-256-cbc";
*/

function reportError($error) {
    echo "<script>console.error('$error')</script>";
}
function reportWarn($error) {
    echo "<script>console.error('$error')</script>";
}

function array_intersect_recursive($array1, $array2) {
    $result = [];
    foreach ($array2 as $key => $value) {
        if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
            $intersect = array_intersect_recursive($array1[$key], $value);
            if (!empty($intersect)) {
                $result[$key] = $intersect;
            }
        } elseif (isset($array1[$key]) && $array1[$key] === $value) {
            $result[$key] = $value;
        }
    }
    return $result;
}


$rootHolder = $_SERVER['DOCUMENT_ROOT']."/database/storage/";

class Jasper {
    public function refresh() {
        echo "<script>window.location.assign(window.location.href)</script>";
    }
    public function create($jsdbname, $error_report = false) {
        try {
            touch("$jsdbname.jdb");
            $jsdbf = fopen("$jsdbname.jdb", "w");
            $txt = Encrypt("[]", $GLOBALS["cipher"], $GLOBALS["key"], $GLOBALS["iv"]);
            fwrite($jsdbf, $txt);
            fclose($jsdbf);
        }
        catch(Exception $e) {
            $errorMessage = "An error occurred while creating the database: " . $e->getMessage();
            if ($error_report) {
                reportError($errorMessage);
            }
        }
    }
    public function get($file, $form = "", $error_report = false) {
      $rootHolder = $_SERVER['DOCUMENT_ROOT']."/database/storage/";

        try {
            $cipher_gt = $GLOBALS["cipher"];
            $key_gt = $GLOBALS["key"];
            $ekey_gt = $GLOBALS["Ekey"];
            $iv_gt = $GLOBALS["iv"];
            $get_data = file_get_contents($rootHolder.$file . ".jdb");
            if ($get_data === false) {
                $errorMessage = "Failed to read the database file.";
                if ($error_report) {
                    reportError($errorMessage);
                }
                return null;
            }
            $scrypt = Decrypt($get_data, $cipher_gt, $key_gt, $iv_gt);
            $decrypt = Decrypt($scrypt, $cipher_gt, $ekey_gt, $iv_gt);
            $result = json_decode($decrypt, true);
            if (empty($result)) {
                $warningMessage = "The database is empty.";
                if ($error_report) {
                    reportWarn($warningMessage);
                }
                return [];
            } else {
                if ($form == "") {
                    return $result;
                } elseif ($form == "reverse") {
                    return array_reverse($result);
                }
            }
        }
        catch(Exception $e) {
            $errorMessage = "An error occurred while retrieving the data: " . $e->getMessage();
            if ($error_report) {
                reportError($errorMessage);
            }
            return null;
        }
    }
    public function put($file, $content, $error_report = false) {
      $rootHolder = $_SERVER['DOCUMENT_ROOT']."/database/storage/";

        try {
            $cipher_gt = $GLOBALS["cipher"];
            $key_gt = $GLOBALS["key"];
            $ekey_gt = $GLOBALS["Ekey"];
            $iv_gt = $GLOBALS["iv"];
            $ycrypt = Encrypt($content, $cipher_gt, $ekey_gt, $iv_gt);
            $encrypt = Encrypt($ycrypt, $cipher_gt, $key_gt, $iv_gt);
            $result = file_put_contents($rootHolder.$file . ".jdb", $encrypt);
            if ($result === false) {
                $errorMessage = "Failed to write data to the database.";
                if ($error_report) {
                    reportError($errorMessage);
                }
            }
        }
        catch(Exception $e) {
            $errorMessage = "An error occurred while writing data to the database: " . $e->getMessage();
            if ($error_report) {
                reportError($errorMessage);
            }
        }
    }
    public function idgen($length = 10, $delim = "") {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0;$i < $length;$i++) {
            $randomString.= $characters[rand(0, $charactersLength - 1) ] . $delim;
        }
        return $randomString;
    }
    public function safe($value) {
        $value = htmlentities($value);
        return $value;
    }
    public function remove_row($file, $code) {
        $givnData = $code;
        $recvDD = $this->get($file);
        foreach ($recvDD as $key => $dd) {
            $result = [];
            if (array_intersect_recursive($dd, $givnData) === $givnData) {
                $result = $dd;
            }
            if (!empty($result)) {
                unset($recvDD[$key]);
                $finecd = json_encode(array_values($recvDD));
                $this->put($file, $finecd);
            }
        }
    }
    public function update_row($file, $code, $ucode) {
        $givnData = $code;
        $updData = $ucode;
        $dataR = $this->get($file);
        foreach ($dataR as $key => $dd) {
            $result = [];
            if (array_intersect_recursive($dd, $givnData) === $givnData) {
                $result = $dd;
            }
            if (!empty($result)) {
                $arrt_upd = $dataR[$key];
                foreach ($updData as $kk => $vv) {
                    $arrt_upd[$kk] = $vv;
                }
                $dataR[$key] = $arrt_upd;
                $finecd = json_encode(array_values($dataR));
                $this->put($file, $finecd);
            }
        }
    }
    public function add_row($file, $code) {
        $givnData = $code;
        $dataV = $this->get($file);
        $dataV[] = $givnData;
        $final = json_encode($dataV);
        $this->put($file, $final);
    }
    public function addJsonDirect($file, ...$code) {
        $dataV = $this->get($file);
        if (count($code) === 1 && is_array($code[0])) {
            $code = $code[0];
        }
        $dataV = array_merge($dataV, $code);
        $final = json_encode($dataV);
        $this->put($file, $final);
    }
    public function get_row($file, $code, $form = "", $error_report = false) {
        $givnData = $code;
        $recvDD = $this->get($file);
        $mmed = [];
        foreach ($recvDD as $key => $dd) {
            if (array_intersect_recursive($dd, $givnData) === $givnData) {
                $mmed[] = $dd;
            }
        }
        if (empty($mmed)) {
            if ($error_report) {
                reportWarn("No matching rows found.");
            }
            return [];
        } else {
            if ($form == "") {
                return $mmed;
            } elseif ($form == "reverse") {
                return array_reverse($mmed);
            }
        }
    }
    public function getKeys($file) {
        $result = $this->get($file);
        if ($result === null) {
            return [];
        } else {
            $keys = [];
            $removeDuplicatesRecursive = function ($array, $parentKey = '') use (&$removeDuplicatesRecursive, &$keys) {
                foreach ($array as $key => $value) {
                    $currentKey = ($parentKey !== '') ? $parentKey . '[' . $key . ']' : $key;
                    if (is_array($value)) {
                        $removeDuplicatesRecursive($value, $currentKey);
                    } else {
                        $keys[] = $currentKey;
                    }
                }
            };
            $removeDuplicatesRecursive($result);
            $lastArrayKeys = array_values(array_unique(array_slice($keys, -count($result))));
            $nka = $this->displayInputArray($lastArrayKeys);
            return $nka;
        }
    }
    private function displayInputArray($inputArray) {
        $result = [];
        foreach ($inputArray as $item) {
            preg_match('/^\d+\[(.*?)\]$/', $item, $matches);
            if (isset($matches[1])) {
                $keys = explode('][', $matches[1]);
                $nestedArray = & $result;
                foreach ($keys as $key) {
                    if (!isset($nestedArray[$key])) {
                        $nestedArray[$key] = [];
                    }
                    $nestedArray = & $nestedArray[$key];
                }
            }
        }
        return $result;
    }
    public function decall($file, $tofl, $error_report = false) {
      $rootHolder = $_SERVER['DOCUMENT_ROOT']."/database/storage/";

        $cipher_gt = $GLOBALS["cipher"];
        $key_gt = $GLOBALS["key"];
        $iv_gt = $GLOBALS["iv"];
        $get_data = file_get_contents($rootHolder.$file . ".jdb");
        if ($get_data === false) {
            $errorMessage = "Failed to read the database file.";
            if ($error_report) {
                reportError($errorMessage);
            }
            return null;
        }
        $decrypt = Decrypt($get_data, $cipher_gt, $key_gt, $iv_gt);
        $result = file_put_contents($tofl . ".json", $decrypt);
        if ($result === false) {
            $errorMessage = "Failed to write decrypted data to the destination file.";
            if ($error_report) {
                reportError($errorMessage);
            }
        }
    }
    public function encall($file, $tofl, $error_report = false) {
        $cipher_gt = $GLOBALS["cipher"];
        $key_gt = $GLOBALS["key"];
        $iv_gt = $GLOBALS["iv"];
        $content = file_get_contents($file . ".json");
        if ($content === false) {
            $errorMessage = "Failed to read the source file.";
            if ($error_report) {
                reportError($errorMessage);
            }
            return null;
        }
        $encrypt = Encrypt($content, $cipher_gt, $key_gt, $iv_gt);
        $result = file_put_contents($tofl . ".jdb", $encrypt);
        if ($result === false) {
            $errorMessage = "Failed to write encrypted data to the destination file.";
            if ($error_report) {
                reportError($errorMessage);
            }
        }
    }
}
function onClick($buttonName, $callback, $optionalAction = null, $callbackParams = null, $customObject = null) {
    if (isset($_POST[$buttonName])) {
        if (is_array($callbackParams)) {
            call_user_func_array($callback, $callbackParams);
        } else {
            $callback($callbackParams);
        }
    } elseif ($optionalAction !== null) {
        $optionalAction();
    }
}
function applyHtmlEntities($value) {
    if (is_array($value)) {
        return array_map('applyHtmlEntities', $value);
    } else {
        return htmlentities($value, ENT_QUOTES, 'UTF-8');
    }
}
?>
