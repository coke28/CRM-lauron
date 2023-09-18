<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use DB;

/**
 *
 */
class CallController extends Controller
{

  public function initVL(Request $request)
  {
    $data = json_decode($request->data, TRUE);

    if ($request->action == 'startCall') {
      return $this->startCall($data);
    }

    if ($request->action == 'trackCall') {
      return $this->trackCall($data);
    }

    if ($request->action == 'getExtensionStatus') {
      return $this->getExtensionStatus($data);
    }
  }

  public function startCall($data)
  {
    $checkVoiceLink = $this->checkVoiceLinkConfig();
    if($checkVoiceLink['success'] == false){
      return $checkVoiceLink;
    }

    $currentUser = User::where('id', auth()->user()->id)->first();

    if($currentUser->onCall == 1){
      return array(
        'success' => false,
        'message' => 'You have an on-going call.',
      );
    }

    //fetch voicelink setup
    $host = env('VOICELINK_HOST');
    $port = env('VOICELINK_PORT', 5038);
    $vluser = env('VOICELINK_USER', 'admin');
    $secret = env('VOICELINK_SECRET', '!@VoicelinK@!');
    $sanitize = env('VOICELINK_SANITIZE_NUMBER', true);

    //additional voicelink setup
    $context = env('VOICELINK_CONTEXT', 'from-internal');
    $waittime = env('VOICELINK_WAITTIME', 30);
    $priority = env('VOICELINK_PRIORITY', 1);
    $maxretry = env('VOICELINK_MAXRETRY', 2);
    $getOutputData = env('VOICELINK_FETCHOUTPUT', true);

    $number = isset($data['number']) ? $data['number'] : null;
    if ($sanitize){
        if (stripos($number, '+630') === 0){
            $number = substr_replace($number, '0', 0, 4);
        }
        else if (stripos($number, '+63') === 0){
            $number = substr_replace($number, '0', 0, 3);
        }
        else if (stripos($number, '630') === 0){
            $number = substr_replace($number, '0', 0, 3);
        }
        else if (stripos($number, '63') === 0){
            $number = substr_replace($number, '0', 0, 2);
        }
    }

    // $call_uniqid = Str::random(10);
     $call_uniqid = auth()->user()->accountID;
    // $accountID = auth()->user()->accountID;
    $callerID = $call_uniqid.(stripos($call_uniqid, '<'.$number.'>') === false ? ' <'.$number.'>' : '');
    // $callerID = auth()->user()->accountID;

    $channel = null;

    if (!empty($currentUser->extension)){
        $channel = "local/".$currentUser->extension."@from-internal";
    }
    // else {
    //     $channel = "local/1111@from-internal";
    // }



    $oSocket = fsockopen($host, $port, $errnum, $errdesc) or die(array('success' => false, 'message' => 'Voicelink config error.'));
    fputs($oSocket, "Action: login\r\n");
    fputs($oSocket, "Events: on\r\n");
    fputs($oSocket, "Username: $vluser\r\n");
    fputs($oSocket, "Secret: $secret\r\n\r\n");

    fputs($oSocket, "Action: originate\r\n");
    fputs($oSocket, "Channel: $channel\r\n");
    fputs($oSocket, "WaitTime: $waittime\r\n");
    fputs($oSocket, "CallerId: $callerID\r\n");
    fputs($oSocket, "Exten: $number\r\n");
    fputs($oSocket, "Context: $context\r\n");

    $accountID = $call_uniqid;
    fputs($oSocket, "Account: $accountID\r\n");

    fputs($oSocket, "Priority: $priority\r\n\r\n");
    fputs($oSocket, "Action: Logoff\r\n\r\n");

    $output = [];
    $originateResponse = false;
    $ctr = 0;
    $linenum = 0;
    $output[$ctr] = [];
    while (!feof($oSocket)) {
        $raw = fgets($oSocket, 8192);
        if ($getOutputData){
            // $output[$ctr] .= $raw;
            $output[$ctr][$linenum] = str_ireplace("\r\n", '', $raw);
        }
        if (stripos($raw, 'Message: Originate successfully queued') !== false){
            $originateResponse = true;
        }
        $linenum++;
    }
    fclose($oSocket);

    $message = '';


    if ($originateResponse) {
      $currentUser->onCall = 1;
      $currentUser->save();
    }
    else {
      $message = "Failed to initiate a call. Please check your setup and try again. If this error message still persist please contact your administrator.";
    }


    return [
        'success' => $originateResponse,
        'message' => $message,
        'originateResponse' => $originateResponse,
        'output' => $output,
        'channel' => $channel,
        'number' => $number,
        'accountID' => $accountID,
    ];
  }

  public function trackCall($data)
  {
    $checkVoiceLink = $this->checkVoiceLinkConfig();
    if($checkVoiceLink['success'] == false){
      return $checkVoiceLink;
    }

    //fetch voicelink setup
    $host = env('VOICELINK_HOST');
    $port = env('VOICELINK_PORT', 5038);
    $vluser = env('VOICELINK_USER', 'admin');
    $secret = env('VOICELINK_SECRET', '!@VoicelinK@!');
    $sanitize = env('VOICELINK_SANITIZE_NUMBER', true);

    ini_set('memory_limit','-1');
    ini_set('max_execution_time', 0);

    $user = User::where('id', auth()->user()->id)->first();
    $extension = $user->extension;
    $vlCCagentNumber = '';
    $number =  isset($data['number']) ? (string) $data['number'] : null;
    if ($sanitize){
        if (stripos($number, '+630') === 0){
            $number = substr_replace($number, '0', 0, 4);
        }
        else if (stripos($number, '+63') === 0){
            $number = substr_replace($number, '0', 0, 3);
        }
        else if (stripos($number, '630') === 0){
            $number = substr_replace($number, '0', 0, 3);
        }
        else if (stripos($number, '63') === 0){
            $number = substr_replace($number, '0', 0, 2);
        }
    }
    $uid = isset($data['uid']) ? $data['uid'] : [];
    $callerID = isset($data['callerID']) ? $data['callerID'] : '';
    $accountID = isset($data['accountID']) && !empty($data['accountID']) ? $data['accountID'] : null;
    $stopOnUp = isset($data['stopOnUp']) ? $data['stopOnUp'] : false;
    $upCount = 0;

    $output = '';
    $arrayOutput = [];
    $out = [];
    $ctr = 0;

    if (isset($data['getOutput']) && $data['getOutput']){
        $out['output'] = [];
    }
    $out['extension'] = $extension;
    $out['agentNumber'] = $vlCCagentNumber;
    $out['answered'] = false;
    $out['numberFound'] = false;
    $out['trackAccountID'] = null;

    do {
      $oSocket = fsockopen($host, $port, $errnum, $errdesc) or die(array('success' => false, 'message' => 'Voicelink config error.'));
      fputs($oSocket, "Action: login\r\n");
      fputs($oSocket, "Username: $vluser\r\n");
      fputs($oSocket, "Secret: $secret\r\n\r\n");

      fputs($oSocket, "Action: Status\r\n\r\n");
      if (isset($data['getChannels']) && $data['getChannels']){
          fputs($oSocket, "Action: Command\r\n");
          fputs($oSocket, "Command: core show channels verbose\r\n\r\n");
          $out['activeCalls'] = 0;
      }
      fputs($oSocket, "Action: Logoff\r\n\r\n");
      $callercheck = false;
      $mcheck = false;
      $cline = false;
      $callStat = false;
      $linenum = 0;
      if (isset($data['debug']) && $data['debug'] == true){
          $arrayOutput[$ctr][$linenum]['line'] = '';
          $arrayOutput[$ctr][$linenum]['cline'] = false;
          $arrayOutput[$ctr][$linenum]['callercheck'] = false;
          $arrayOutput[$ctr][$linenum]['mcheck'] = false;
          $arrayOutput[$ctr][$linenum]['callStat'] = false;
          $arrayOutput[$ctr][$linenum]['new_uid'] = 'none';
      }
      $output = '';
      $raw = '';
      $onParagraph = false;
      $bridgeChannelCheck = false;
      $ignoreParagraph = false;
      $extenCheck = false;
      $agentNumCheck = false;
      $numberCheck = false;
      $accountIDcheck = false;
      $stateCheck = false;
      $answered = false;
      if (isset($data['getOutput']) && $data['getOutput']){
          $out['output'][$ctr] = [];
      }
      while (!feof($oSocket)) {
        $raw = fgets($oSocket, 7192);
        if (isset($data['getOutput']) && $data['getOutput']){
            // $out['output'][$ctr] .= $raw;
            $out['output'][$ctr][$linenum] = str_ireplace("\r\n", '', $raw);
        }
        if (stripos($raw, "\r\n") === 0 || stripos($raw, 'Response: Goodbye') !== false){
            if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- OFF PARAGRAPH"; }
            // if ( $settings->autoCallProcess == 'predictiveVL' ){
                if ( !$answered && (($extenCheck || $agentNumCheck) && ($numberCheck || $accountIDcheck) && $stateCheck) ){
                    $answered = true;
                    $out['answered'] = true;
                    // if ($answered && $stopOnUp){
                    //     break;
                    // }
                }else if ( !$answered && stripos($raw, 'Response: Goodbye') !== false && !(isset($data['waitToUp']) && $data['waitToUp'] && ($numberCheck || $accountIDcheck)) ){
                    $out['debug']['numCheck'] = $numberCheck;
                    $out['debug']['accIDcheck'] = $accountIDcheck;
                    $out['debug']['isWaitToUp'] = isset($data['waitToUp']) && $data['waitToUp'];
                    if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= " = CONDITIONS UNMET\r\n"; }
                    $callStat = false;
                }
            // }
            $onParagraph = false;
            $ignoreParagraph = false;

            $extenCheck = false;
            $agentNumCheck = false;
            // $numberCheck = false;
            // $accountIDcheck = false;
            $stateCheck = false;
        }else if ($ignoreParagraph){
            $callStat = false;
            continue;
        }
        if (stripos($raw, 'ConnectedLineName') !== false || stripos($raw, 'CallerIDName') !== false || stripos($raw, 'ConnectedLineNum') !== false || stripos($raw, 'CallerIDNum') !== false || stripos($raw, 'Channel') !== false){
            // if (stripos($raw, $vlCCagentNumber) !== false){
            if (!empty($vlCCagentNumber) && stripos($raw, 'Agent/'.$vlCCagentNumber) !== false){
                $agentNumCheck = true;
                if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- AGENT NUMBER FOUND\r\n"; }
            // }else if (stripos($raw, $extension) !== false){
            // }else if (stripos(explode(': ',str_ireplace("\r\n", '', $raw))[1], $extension) === 0 ){
            }else if (explode(': ',str_ireplace("\r\n", '', $raw))[1] == $extension){
                $extenCheck = true;
                if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- EXTENSION FOUND\r\n"; }
            }else if (stripos($raw, $number) !== false){
                $numberCheck = true;
                if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- PHONE NUMBER FOUND\r\n"; }
                $out['numberFound'] = true;
            }
            if (stripos($raw, $callerID) !== false || stripos($raw, $extension) !== false || stripos($raw, $number) !== false){
                if (isset($data['debug']) && $data['debug'] == true){
                    $arrayOutput[$ctr][$linenum]['line'] = $raw;
                    $arrayOutput[$ctr][$linenum]['callStat'] = true;
                }
                $callStat = true;
                $onParagraph = true;
                // if ($ctr > 0){
                //     $answered = true;
                //     $out['answered'] = true;
                // }
            }
        }else if ( (stripos($raw, 'Accountcode') !== false || stripos($raw, 'Account:') !== false) && ((!empty($accountID) && stripos($raw, $accountID) !== false) || (empty($accountID) && stripos($raw, env('VOICELINK_ACCOUNTCODE_PREFIX','infolinkcrm')) !== false) && ($extenCheck || $agentNumCheck)) ){
            $accountIDcheck = true;
            $out['trackAccountID'] = explode(': ',str_ireplace("\r\n", '', $raw))[1];
            if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- ACCOUNT ID FOUND\r\n"; }
            $callStat = true;
            $onParagraph = true;
            // if ($ctr > 0){
            //     $answered = true;
            //     $out['answered'] = true;
            // }
        }else if ($onParagraph && stripos($raw, 'BridgedChannel') !== false){
            $bridgeChannelCheck = true;
            // if (stripos($raw, $vlCCagentNumber) !== false){
            if (!empty($vlCCagentNumber) && stripos($raw, 'Agent/'.$vlCCagentNumber.' Login') !== false){
                $agentNumCheck = true;
                if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- AGENT NUMBER FOUND\r\n"; }
            // }else if (stripos($raw, $extension) !== false){
            // }else if (stripos(explode(': ',str_ireplace("\r\n", '', $raw))[1], $extension) === 0){
            }else if (explode(': ',str_ireplace("\r\n", '', $raw))[1] == $extension){
                $extenCheck = true;
                if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- EXTENSION FOUND\r\n"; }
            }else if (stripos($raw, $number) !== false){
                $numberCheck = true;
                if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- PHONE NUMBER FOUND\r\n"; }
                $out['numberFound'] = true;
            }
        }else if ($onParagraph && (stripos($raw, 'State: Down') !== false || stripos($raw, 'State: Ringing') !== false)){
            // $answered = false;
            // $out['answered'] = false;
            $stateCheck = false;
            if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- DOWN\r\n"; }
        }else if ($onParagraph && stripos($raw, 'State: Up') !== false){
            $stateCheck = true;
            if (isset($data['getOutput']) && $data['getOutput']){ $out['output'][$ctr][$linenum] .= "<-- UP\r\n"; }
        }else if ($onParagraph && (stripos($raw, 'BridgedUniqueid') !== false || stripos($raw, 'Uniqueid') !== false) ){
            $lineUID = explode(': ',str_ireplace("\r\n", '', $raw))[1];
            if (!in_array($lineUID, $uid)){
                $uid[] = $lineUID;
            }
        }

        if (isset($data['getChannels']) && $data['getChannels'] && stripos($raw, 'AppDial') !== false){
            $out['activeCalls']++;
        }


        // else if (stripos($raw, 'Uniqueid') !== false && $cline == true){
        //     if (isset($data['debug']) && $data['debug'] == true){
        //         $arrayOutput[$ctr][$linenum]['line'] = $raw;
        //     }

        //     $line = explode(': ',$raw);
        //     $udata = trim($line[1],"\r\n");
        //     if (!in_array($udata, $uid)){
        //         $uid[] = $udata;
        //         if (isset($data['debug']) && $data['debug'] == true){
        //             $arrayOutput[$ctr][$linenum]['new_uid'] = $udata;
        //         }
        //     }

        //     $cline = false;
        //     $callercheck = false;

        //     $mcheck = false;
        // }
        // else if (stripos($raw, 'ConnectedLineNum') !== false && $callercheck == true){
        //     if (isset($data['debug']) && $data['debug'] == true){
        //         $arrayOutput[$ctr][$linenum]['line'] = $raw;
        //     }

        //     $line = explode(': ',$raw);
        //     if (stripos($raw, $number) !== false && $mcheck == false){
        //         $cline = true;
        //         if (isset($data['debug']) && $data['debug'] == true){
        //             $arrayOutput[$ctr][$linenum]['cline'] = true;
        //         }
        //     }else if (stripos($raw, $extension) !== false && $mcheck == true){
        //         $cline = true;
        //         if (isset($data['debug']) && $data['debug'] == true){
        //             $arrayOutput[$ctr][$linenum]['cline'] = true;
        //         }
        //     }else{
        //         $callercheck = false;
        //     }
        // }
        // else if (stripos($raw, 'CallerIDNum') !== false ){
        //     if (isset($data['debug']) && $data['debug'] == true){
        //         $arrayOutput[$ctr][$linenum]['line'] = $raw;
        //     }

        //     $line = explode(': ',$raw);
        //     if (stripos($raw, $number) !== false){
        //         $mcheck = true;
        //         $callercheck = true;
        //         if (isset($data['debug']) && $data['debug'] == true){
        //             $arrayOutput[$ctr][$linenum]['callercheck'] = true;
        //             $arrayOutput[$ctr][$linenum]['mcheck'] = true;
        //         }
        //     }else if (stripos($raw, $extension) !== false){
        //         $mcheck = false;
        //         $callercheck = true;
        //         if (isset($data['debug']) && $data['debug'] == true){
        //             $arrayOutput[$ctr][$linenum]['callercheck'] = true;
        //             $arrayOutput[$ctr][$linenum]['mcheck'] = false;
        //         }
        //     }
        // }
        $linenum++;
      }
      // $rawOutputArray[] = $output;
      fclose($oSocket);
      $ctr++;
      if ($answered && $stopOnUp){
          break;
      }
      sleep(1);
    } while ($callStat);
    $out['uids'] = $uid;
    $out['params'] = $data;
    $user->onCall = $answered ? 1 : 0;
    $user->save();


    if (isset($data['debug']) && isset($data['debugJsonOutput']) && $data['debug'] == true && $data['debugJsonOutput'] == true){
        return ['extension' => $extension, 'number' => $number, 'uids' => $uid, 'output' => $arrayOutput];
    } else if (isset($data['debug']) && $data['debug'] == true){
        echo '<pre>';
        print_r(['extension' => $extension, 'number' => $number, 'uids' => $uid, 'output' => $arrayOutput]);
        die();
    }
    return $out;
  }

  public function getExtensionStatus($data)
  {
    $checkVoiceLink = $this->checkVoiceLinkConfig();
    if($checkVoiceLink['success'] == false){
      return $checkVoiceLink;
    }

    //fetch voicelink setup
    $host = env('VOICELINK_HOST');
    $port = env('VOICELINK_PORT', 5038);
    $vluser = env('VOICELINK_USER', 'admin');
    $secret = env('VOICELINK_SECRET', '!@VoicelinK@!');
    $sanitize = env('VOICELINK_SANITIZE_NUMBER', true);


    $user = User::where('id', auth()->user()->id)->first();
    $extension = $user->extension;

    $oSocket = fsockopen($host, $port, $errnum, $errdesc) or die(array('success' => false, 'message' => 'Voicelink config error.'));
    fputs($oSocket, "Action: login\r\n");
    fputs($oSocket, "Username: $vluser\r\n");
    fputs($oSocket, "Secret: $secret\r\n\r\n");
    fputs($oSocket, "Action: SIPPeers\r\n\r\n");
    fputs($oSocket, "Action: Logoff\r\n\r\n");
    $objectname = false;
    while (!feof($oSocket)) {
        $raw = fgets($oSocket, 8192);
        // $output .= $raw;
        // $output .= '<br>';
        if (stripos($raw, 'Status:') !== false && $objectname == true){
            $status = explode('(', explode(':',$raw)[1])[0];
            $objectname = false;
        }
        if (stripos($raw, 'ObjectName:') !== false ){
            if (explode(':',$raw)[1] == $extension){
                $objectname = true;
            }
        }
    }
    return (isset($status) && $status == ' OK ' ? true : false);

  }

  public function checkVoiceLinkConfig()
  {
    if (env('VOICELINK_HOST',false) && !empty(env('VOICELINK_HOST')) && env('VOICELINK_ENABLE',true)) {
      return array('success' => true);
    }
    else {
      return array('success' => false, 'message' => 'No Voicelink Connection!');
    }
  }

}













?>
