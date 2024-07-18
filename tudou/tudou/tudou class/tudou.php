<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Builders\MessageBuilder;
use Discord\Parts\User\Activity;

class config
{
    public $imaginecount = 337;
    public $embedbool = 1;
    public $hexcode = 0xff8890;
}

class flags 
{
    public $stopflag = 0;
}

class tudou
{
    public $config;
    public $flags;
    public $activity = "tudou!help";

    public $token = "(your token)";

    public $apilink;
    public $imaginecount;


    function __construct() 
    {
        $this->flags = new flags();

        $this->config = new config();

        $jsoncontent = file_get_contents("config.json");
        $jsondecoded = json_decode($jsoncontent, true);
        
        $this->config->embedbool = $jsondecoded["embed"];
        $this->config->hexcode = $jsondecoded["hex"];


        $content = file_get_contents("https://raw.githubusercontent.com/deplantis/tudou/main/api.json");

        if ($content == false)
        {
            // set default settings

            $this->apilink = "https://raw.githubusercontent.com/deplantis/tudou/main/tudou/"; 
            $this->imaginecount = 337;
        }
        else
        {
            $jsondecoded = json_decode($content, true);           
 
            $this->apilink = $jsondecoded["link"];
            $this->imaginecount = $jsondecoded["count"];
        }
    }

    public function Run(Discord $discord)
    {
        echo "lol\n";

        echo "config: \n";
        echo  $this->config->embedbool ? "embed bool: true\n" : "embed bool: false\n";
        echo "hex code: " . $this->config->hexcode . "\n";

        echo "\n";

        echo "api: \n";
        echo "url: ". $this->apilink . "\n";
        echo "imagine count: ". $this->imaginecount. "\n";


        $loop = $discord->getLoop();
        $loop->addPeriodicTimer(30, function ()
        {
            global $discord;
            $this->Update($discord);
        });
    
        $activity = $discord->factory(Activity::class, [
            "type" => Activity::TYPE_PLAYING,
            "name" => $this->activity,
        ]);
        $discord->updatePresence($activity);

    }

    public function Update(Discord $discord)
    {
        $jsoncontent = file_get_contents("config.json");
        $jsondecoded = json_decode($jsoncontent, true);
        
        $this->config->embedbool = $jsondecoded["embed"];
        $this->config->hexcode = $jsondecoded["hex"];
        
        
        $content = file_get_contents("https://raw.githubusercontent.com/deplantis/tudou/main/api.json");

        if ($content == false)
        {
            // set default settings

            $this->apilink = "https://raw.githubusercontent.com/deplantis/tudou/main/tudou/"; 
            $this->imaginecount = 337;
        }
        else
        {
            $jsondecoded = json_decode($content, true);           
 
            $this->apilink = $jsondecoded["link"];
            $this->imaginecount = $jsondecoded["count"];
        }

        if ($this->flags->stopflag == 1)
        {
            $discord->close();
        }

        echo "update function called\n";

        echo "lol\n";

        echo "\n";

        echo "config: \n";
        echo  $this->config->embedbool ? "embed bool: true\n" : "embed bool: false\n";
        echo "hex code: " . $this->config->hexcode . "\n";

        echo "\n";

        echo "api: \n";
        echo "url: ". $this->apilink . "\n";
        echo "imagine count: ". $this->imaginecount. "\n";

        echo "\n";

    }

    public function generate(Discord $discord, Message $message)
    {
        $url = $this->apilink . rand(1,$this->config->imaginecount) . ".gif";

        if ($this->config->embedbool == 1)
        {
            $embed = new Embed($discord);
            $embed->setImage($url);
            $embed->setColor($this->config->hexcode);
            $message->channel->sendEmbed($embed);
        }
        else
        {
            $messagebuilder = MessageBuilder::new()->setContent($url); 
            $message->channel->sendMessage($messagebuilder);
        }
    }

    public function choice(Discord $discord, Message $message, $count)
    {
        $url = $this->apilink . $count . ".gif";

        if ($count <= $this->imaginecount && $count > 0)
        {
            if ($this->config->embedbool == 1)
            {
                $embed = new Embed($discord);
                $embed->setImage($url);
                $embed->setColor($this->config->hexcode);
                $message->channel->sendEmbed($embed);
            }
            else
            {
                $messagebuilder = MessageBuilder::new()->setContent($url); 
                $message->channel->sendMessage($messagebuilder);
            }
       }
       else
       {

        $embed = new Embed($discord);
        $embed->setTitle("tudou!choice");
        $embed->addFieldValues("error","invalid range, max is ". $this->config->imaginecount);
        $embed->setColor($this->config->hexcode);
        $message->channel->sendEmbed($embed);
       }
    }

    public function WriteToConfig($embedbool, $hexcode)
    {
        $json = array(
            "embed" => $embedbool,
            "hex" => sprintf($hexcode)
        );

        $jsonencoded = json_encode($json, JSON_PRETTY_PRINT);

        $OpenFile = fopen("config.json", "w");
        fwrite($OpenFile, $jsonencoded);
        fclose($OpenFile);
    }
}

?>
