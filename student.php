<?php
require_once('prepage.php');
session_start();

function inbloom_curl_call( $url ){
  $ch = curl_init();
  $token = $_SESSION['access_token'];
  $code = $_SESSION['code'];
  $auth = sprintf('Authorization: bearer %s', $token);
  $headers = array(
    'Content-Type: application/vnd.slc+json',
    'Accept: application/vnd.slc+json',
    $auth);
  error_log( "Authorization: $auth" );

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_POST, FALSE);
  curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

  if ( DISABLE_SSL_CHECKS == TRUE) {
  // WARNING: this would prevent curl from detecting a 'man in the middle' attack
  // See note in settings.php 
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  }

  $result = curl_exec($ch);
  curl_close($ch);
  return json_decode( $result );
}

$url = sprintf('https://api.sandbox.inbloom.org/api/rest/v1/students/%s', $_GET['UUID']);
$student = inbloom_curl_call( $url );

$attendances_url = sprintf('https://api.sandbox.inbloom.org/api/rest/v1/students/%s/attendances',$_GET['UUID']);
$attendances = inbloom_curl_call( $attendances_url );

$assessments_url = ('https://api.sandbox.inbloom.org/api/rest/v1/studentAssessments');
$assessments = inbloom_curl_call( $assessments_url );

$discipline_url = ('https://api.sandbox.inbloom.org/api/rest/v1/studentDisciplineIncidentAssociations');
$disciplines = inbloom_curl_call( $discipline_url );

$gradebook_url = sprintf('https://api.sandbox.inbloom.org/api/rest/v1/students/%s/studentGradebookEntries',$_GET['UUID']);
$gradebooks = inbloom_curl_call( $gradebook_url );

include_once( "openbadges.class.php" );
$obadge = new openBadgeDisplay( "http://beta.openbadges.org" );
$groups = $obadge->findGroups( "jbkc85@gmail.com" );

?>

    <script>
/**
      $(function(){
        $(".submit").click( function(){
          var msg_type = $("#message_type");
          var msg = $("#message_content");
          var recipient = $("#recipient");
          var sender = $("#sender");
          var post = "msg_type="+msg_type+"&msg="+msg+"&recipient="+recipient+"&sender="+sender;
          if( msg_type == ''|| msg == ''||recipient==''||sender=='' ){
            alert("Invalid Message");
          }
          $.ajax({
            type: "POST",
            url: "postmessage.php",
            data: post,
            cache: false,
            success: function( response ){
              $("#messages").append( response );
            }
          });
          //return false;
        });
      });
 */
    </script>
  <h2 style="text-align: center"><?php echo $student->name->firstName . ' ' . $student->name->lastSurname ?></h2>

  <div data-role"collapsible-set" data-mini="true" data-theme="a" data-inset="false" id="mpage">
  <div data-role='collapsible' data-collapsed='false'>
    <h1>Student Attendance</h1>
    <div data-role"collapsible-set" data-mini="true" data-theme="a" data-inset="false" id="mpage1">
<?php
    foreach( $attendances as $attendance ){
      foreach( $attendance->schoolYearAttendance as $schoolYear ){
        $eventlist = "";
        foreach( $schoolYear->attendanceEvent as $event ){
          if( $event->event != "In Attendance" ){
            $eventlist .= "<li>$event->event on $event->date";
            $missedAssignments = inbloom_curl_call( "https://api.sandbox.inbloom.org/api/rest/v1.1/gradebookEntries?dateAssigned={$event->date}" );
            foreach( $missedAssignments as $assignment ){
              $eventlist .= "<ul>";
              $eventlist .= "<li>Missed {$assignment->gradebookEntryType}</li>";
              $eventlist .= "</ul>";
            }
            $eventlist .= "</li>";
          }
        }
        if( $eventlist == "" ){
        } else {
          echo "<div data-role='collapsible' data-collapsed='true'>";
          echo "<h1>$schoolYear->schoolYear</h1>";
          echo "<ul>";
          echo $eventlist;
          echo "</ul>";
          echo "</div>";
        }
      }
    }
?>
    </div>
  </div>
  <div data-role='collapsible' data-collapsed='true' >
    <h1>Student Gradebook Entries</h1>
    <div data-role="collapsible-set" data-mini='true' data-theme='a' data-inset='false' id='mpage3'>
<?php
      foreach( $gradebooks as $grade ){
        $section = inbloom_curl_call( "https://api.sandbox.inbloom.org/api/rest/v1.1/sections/".$grade->sectionId );
        $gradeid = $grade->id;
        if( preg_match("/^(C|D|F)/i",$grade->letterGradeEarned) ){
          echo "<div data-role='collapsible' data-collapsed='true' data-theme='e'>";
        } else {
          echo "<div data-role='collapsible' data-collapsed='true'>";
        }
        echo "<h1>$section->uniqueSectionCode - $grade->letterGradeEarned</h1>";
        echo "<p>Letter Grade: $grade->letterGradeEarned earned on $grade->dateFulfilled</p>";
        $othergrades = inbloom_curl_call( "https://api.sandbox.inbloom.org/api/rest/v1.1/studentGradebookEntries?gradebookEntryId=$grade->gradebookEntryId" );
        //echo "Other Grades: ".print_r($othergrades);
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $f = 0;
        foreach( $othergrades as $othergrade ){
          if (preg_match("/^A/i",$othergrade->letterGradeEarned)){
            $a++;
          } else if (preg_match("/^B/i",$othergrade->letterGradeEarned)){
            $b++;
          } else if (preg_match("/^C/i",$othergrade->letterGradeEarned)){
            $c++;
          } else if (preg_match("/^D/i",$othergrade->letterGradeEarned)){
            $d++;
          } else {
            $f++;
          }
        }
?>
<script type='text/javascript'>
  google.load('visualization','1', { packages:['corechart'], "callback":drawChart()});
  google.setOnLoadCallback(drawChart);
  function drawChart(){
    var data = google.visualization.arrayToDataTable([
      ['Grade Letter', 'Number of Grades'],
      ['A',<?=$a?>],
      ['B',<?=$b?>],
      ['C',<?=$c?>],
      ['D',<?=$d?>],
      ['F',<?=$f?>]
    ]);
    var options = {
      title: "Gradebook Entry Comparisons",
      vAxis: { title: "Grade Entries" }
    };

    var chart= new google.visualization.BarChart(document.getElementById('chart_div-<?=$gradeid?>'));
    chart.draw( data, options );
  }
</script>
        <div id='chart_div-<?=$gradeid?>'></div>

<?
        echo "</div>";
      }
?>
    </div>
  </div>
  <div data-role='collapsible' data-collapsed='true'>
    <h1>Student Assessments</h1>
    <div data-role="collapsible-set" data-mini='true' data-theme='a' data-inset='false' id='mpage2'>
<?php
      foreach( $assessments as $assessment ){
        if( $assessment->studentId == $_GET['UUID'] ){
          echo "<div data-role='collapsible' data-collapsed='true'>";
          echo "<h1>$assessment->gradeLevelWhenAssessed Assessment on $assessment->administrationEndDate</h1>";
          echo "<p>Results:";
          if( $assessment->reasonNotTested == "Medical waiver" ){
            echo "Medical Waiver Presented";
          }
          echo "<ul>";
          foreach( $assessment->scoreResults as $score ){
            echo "<li>$score->result - $score->assessmentReportingMethod</li>";
          }
          echo "</ul>";
          echo "</div>";
        }
      }
?>
    </div>
  </div>
  <div data-role='collapsible' data-collapsed='true'>
    <h1>Student Messaging</h1>
    <div id='messaging'>
<?php
    $sql = "SELECT n.message_type, n.message, n.recipient, n.sender, n.created_at, u.id as userid, u.username as user FROM notifications n, users u WHERE u.inbloomid = '{$_GET['UUID']}' AND (n.sender = u.id OR n.recipient = u.id) order by created_at DESC";
    $userid = 0;
    if( $results = $mysqli->query($sql) ){
      while( $row = $results->fetch_assoc() ){
        $userid = $row['userid'];
        $usersend = mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT * FROM users where id = {$row['sender']}"));
        $userrec  = mysqli_fetch_assoc(mysqli_query($mysqli,"SELECT * FROM users where id = {$row['recipient']}"));
    
        if( $row['message_type'] == 'alert' ){
          echo "<div data-role='header'>";
          echo   "ALERTED {$userrec['username']} on {$row['created_at']}";
          echo "</div>";
          echo "<div class='ui-body ui-body-e'>";
        }
        else if( $row['sender'] == $row['userid'] ){
          echo "<div data-role='header'>";
          echo   "Sent Message to {$userrec['username']} on {$row['created_at']}";
          echo "</div>";
          echo "<div class='ui-body ui-body-a'>";
        } else {
          echo "<div data-role='header'>";
          echo   "Received Message from {$usersend['username']} on {$row['created_at']}";
          echo "</div>";
          echo "<div class='ui-body ui-body-c'>";
        }
        switch( $row['message_type'] ){
          case "alert":
            echo "ALERT: ".strtoupper($row['message']);
            break;
          default:
            echo $row['message'];
        }
        echo "</div>";
      }
    }
?>
    </div>
    <div style='float:right;'>
    <br /><br />
    <div data-role='popup' id='createmessage' data-theme='a'>
      <form style='padding:10px 20px;' method='post' action='postmessage.php' >
        <select name='msg_type' id='msg_type'>
          <option value='notification'>Notification</option>
          <option value='alert'>Alert</option>
          <option value='cancellation'>Cancellation</option>
        </select>
        <br />
        <input type='text' value='Message..' id='msg_content' name='msg_content'></input>
        <input type='hidden' value='1' id='recipient' name='recipient'></input>
        <input type='hidden' value='29' id='sender' name='sender'></input>
        <input type='hidden' value='<?=$_GET['UUID'];?>' name='uuid'></input>
        <br /><br />
        <button type='submit' class='submit' data-theme='b'>Send a New Message</button>
      </form>
    </div>
    </div>
    </div>

<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <div id='openbadges'>
<?php
      foreach( $groups->groups as $obgroup ){
        $groupbadges = json_decode($obadge->display("jbkc85@gmail.com",$obgroup->groupId));
        $output = "<center>";
        foreach( $groupbadges->badges as $badge ){
          $assertion = $badge->assertion->badge;
          $output .= "<h4>$assertion->name from ".$assertion->issuer->name."</h4><a href='".$assertion->issuer->origin."'><img src='$assertion->image'></a><br /><a href='".$assertion->criteria."'>Criteria/Evidence</a>";
        }
        $output .= "</center>";
        echo $output;
      }
?>
    </div>
        
    <div data-role="footer" data-position="fixed"> 
	<h1></h1> 
</div>
    <script>
      google.load('visualization','1', { packages:['corechart'], "callback":drawChart()});
    </script>
  </body>
</html>
