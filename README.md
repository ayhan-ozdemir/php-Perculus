<font size=1>
<b># phpPerculus</b><br>
This class includes PHP-based functions that simplify the use of the PERCULUS API.<br>
Bu sınıf, PERCULUS API'sinin kullanımını basitleştiren PHP tabanlı işlevler içerir.<br><br>
<b>Example - Örnek</b><br>
<div>
<pre>
<code>
<?php
// Get a token from perculus system
// If there is no error, a value occurs in the accessToken variable. If there is an error, no value is generated.
// Perculus sisteminden jeton al
// Hata yoksa, accessToken değişkeninde bir değer oluşur. Bir hata varsa, hiçbir değer üretilmez.
$perculus = new phpPerculus();
echo $perculus->accessToken."<br>";
echo $perculus->tokenErrorCode."<br>";
echo $perculus->tokenErrorDescription."<br>";
?>
</code>
</pre>
</div>
</font>
