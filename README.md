<b># phpPerculus</b><br>
This class includes PHP-based functions that simplify the use of the PERCULUS API.<br>
Bu sınıf, PERCULUS API'sinin kullanımını basitleştiren PHP tabanlı işlevler içerir.<br><br>
<b>Example - Örnek</b><br>
<pre>
<code>
<tt>
&lt;?php
include "classPerculus.php";
// Get a token from perculus system
// If there is no error, a value occurs in the accessToken variable. If there is an error, no value is generated.
// Perculus sisteminden jeton al
// Hata yoksa, accessToken değişkeninde bir değer oluşur. Bir hata varsa, hiçbir değer üretilmez.
$perculus = new phpPerculus();
echo $perculus->accessToken;
echo $perculus->tokenErrorCode;
echo $perculus->tokenErrorDescription;

if ( $perculus->accessToken <> '' )
{
    // Create a virtual classroom - Sanal sınıf oluştur.
    $perculus->createClassroom("CHEMISTRY LESSON", "ENGINEERING FACULTY", "2020-06-28T23:00:00", "80", "ENGINEERING FACULTY");
    echo $perculus->classroomID;
    echo $perculus->classroomErrorCode;
    echo $perculus->classroomErrorDescription;
    
    if ( $perculus->classroomID <> '' )
    {
       // Add a participant - Katılımcı ekle.
       $perculus->addParticipant($perculus->classroomID,"ayhan","AYHAN","ÖZDEMİR","ayhan@cumhuriyet.edu.tr","a","505xxxxxxx");
       echo $perculus->participantID;
       echo $perculus->participantErrorCode;
       echo $perculus->participantErrorDescription;
    }
}
?&gt
</tt>
</code>
</pre>
