<?php

class botClass {

    //Variables

    //put the token in the code
    public $token = "124799479:AAERYm4q4o1VghAEUSrp0TSa-pJeUiTu2_c";



    //Functions


    //!!!the numbering in php starts from 0!!!

    //set website
    public function website(){
        $website = "https://api.telegram.org/bot".$this->token;
        return $website;
    }


    //get the messages into json format
    public function update_messages($website){
        $update = file_get_contents($website."/getupdates");
        //$update = file_get_contents("php://input");
        return $update;
    }


    //decodes the json messages to an array
    public function json_to_array($update){
        $updateArray = json_decode($update, TRUE);
        return $updateArray;
    }


    //prints the json data
    public function print_json($json){
        print_r($json);
    }


    //prints the array data
    public function print_array($array){
        print_r($array);
    }


    //finds the length of the array !!!not the number of the messages!!!
    public function array_length($array){
        $length_array = count($array, COUNT_RECURSIVE);
        return $length_array;
    }


    //calculate the number of messages
    public function count_messages($array){
        $p=0;
        foreach ($array['result'] as $message) {
            $p++;
        }
        return $p;
    }


    public function get_array($website){
        //access the website and save the updated messages
        $update = $this->update_messages($website);
        //make $update an array
        $updateArray = $this->json_to_array($update);
        return $updateArray;
    }


    //return the id of the user who wrote the message_numbered-th message
    public function get_chat_id($array, $message_number){
        $chatId = $array["result"][$message_number-1]["message"]["chat"]["id"];
        return $chatId;
    }


    //return the first name of the user who wrote the message_numbered-th message
    public function get_first_name($array, $message_number){
        $first_name=$array["result"][$message_number-1]["message"]["from"]["first_name"];
        return $first_name;
    }


    //return the last name of the user who wrote the message_numbered-th message
    public function get_last_name($array, $message_number){
        $last_name=$array["result"][$message_number-1]["message"]["from"]["last_name"];
        return $last_name;
    }

    //print the id of the user who wrote the y-th message in the x-th array data
    public function get_text($array, $message_number){
        $text = $array["result"][$message_number-1]["message"]["text"];
        return $text;
    }

    //if message of the text is the same with the $desired_message !!!the numbering of php starts from 0!!!
    public function if_message($array, $message_number, $desired_message){
        if ($array["result"][$message_number-1]["message"]["text"]==$desired_message)
            return 1;
        else
            return 0;
    }


    //send a message to that user
    public function send_message($site,$id,$message){
        file_get_contents($site."/sendmessage?chat_id=".$id."&text=$message");
    }


    //removes special characters from string
    public function clean($string){
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }






}







//database

//creates an array with the salutations words
$hello=["hi","hej","hello"];




//end of database






//create a new bot object with the given token
$bot = New botClass();

//the bot's website
$website = $bot->website();

//get the messages $updateArray in order to initialize the counter
$updateArray=$bot->get_array($website);

//initialize the counter to '0' (equal to the number of the last message)
$counter=$bot->count_messages($updateArray);



//starts the loop of the bot
while (1){

    //count the number of messages
    $number_of_messages=$bot->count_messages($updateArray);
    //count the number of messages for php (php starts counting at 0)
    $number_of_messages_php=$number_of_messages-1;

    //when telegram receives a new message, this code is executed
    if ($counter<$number_of_messages) {
        //finds last message with the special characters
        $last_message_with=$updateArray["result"][$number_of_messages_php]["message"]["text"];
        //last message without the special characters
        $last_message=$bot->clean($last_message_with);
        //get user's id
        $chatid = $bot->get_chat_id($updateArray, $number_of_messages);
        //get user's first name
        $first_name=$bot->get_first_name($updateArray,$number_of_messages);
        //get user's last name
        $last_name=$bot->get_last_name($updateArray,$number_of_messages);



        //if last message is contained in.....do.....
        if (in_array($last_message,$hello)) {
            $bot->send_message($website, $chatid, "hi ".$first_name);
        }


        //update the counter
        $counter = $counter + 1;

    }

    //update the $updateArray
    $updateArray=$bot->get_array($website);
}

//test area
//working code

//echo $bot->clean($updateArray["result"][$number_of_messages_php]["message"]["text"]);
//$bot->print_json($updateArray);
//$bot->print_array($updateArray);
//echo $message_number=$bot->count_messages($updateArray);
//$chatid = $bot->get_chat_id($updateArray, $bot->count_messages($updateArray));
//$bot->send_message($website,$chatid,"hej");
/*if (in_array($updateArray["result"][$bot->count_messages($updateArray)-1]["message"]["text"],$hello)){
    echo "1";
}*/
//if ($bot->if_message($updateArray, $bot->count_messages($updateArray), "hi"))
//$bot->send_message($website, $chatid, "hi you");
//echo $bot->get_text($updateArray,$bot->count_messages($updateArray));

?>
