<?php
use phpFastCache\CacheManager;


require("setting.php");

$InstanceCache = CacheManager::getInstance('redis');

      
$key = "product_page";
$CachedString = $InstanceCache->getItem($key);
if (is_null($CachedString->get())) {
    //$CachedString = "APC Cache --> Cache Enabled --> Well done !";
    // Write products to Cache in 10 minutes with same keyword
    $CachedString->set("Redis Cache --> Cache Enabled --> Well done !")->expiresAfter(60);
    $InstanceCache->save($CachedString);
    echo "FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ";
    echo $CachedString->get();
} else {
    echo "READ FROM CACHE // ";
    echo $CachedString->get();
}
echo '<br /><br /><a href="/">Back to index</a>&nbsp;--&nbsp;<a href="./' . basename(__FILE__) . '">Reload</a>';





