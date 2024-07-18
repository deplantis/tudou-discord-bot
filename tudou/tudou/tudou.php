<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Discord\Builders\MessageBuilder;

include "tudou class\\tudou.php";
$Tudou = new Tudou;

$discord = new Discord([
    'token' => $Tudou->token,
    'intents' => Intents::getDefaultIntents()
]);

$discord->on("ready", [$Tudou, "Run"]);

$discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) 
{
    global $Tudou; 

    $messagecontent = strtolower($message->content); // so commands with a capitalize character can also be used 

    if ($messagecontent == "tudou!hello" || $messagecontent == "t!hello") 
    {
        $messagebuilder = MessageBuilder::new()->setContent('HELLOOOOO FREINDD :DD'); 
        $message->channel->sendMessage($messagebuilder);
    }

    if ($messagecontent == "tudou!help" || $messagecontent == "t!help") 
    {
        $embed = new Embed($discord);
        $embed->setTitle("Tudou help");
        $embed->addFieldValues("tudou!help", "give the list of all commands of the bot", false);
        $embed->addFieldValues("tudou!information", "give information about the bot", false);
        $embed->addFieldValues("tudou!generate / tudou!imagine", "give aa randomm generatedd TUDOUU PICTUREE!! :DD YAYY", false);
        $embed->addFieldValues("tudou!choice (number)", "choice a specific tudou picture", false);
        $embed->setColor(0xff8890);
        $message->channel->sendEmbed($embed);
    }

   
    if ($messagecontent == "tudou!information" || $messagecontent == "tudou!info" || $messagecontent == "t!information" || $messagecontent == "t!info") 
    {
        $embed = new Embed($discord);
        $embed->setTitle("Tudou information");
        $embed->addFieldValues("tudou discord server", "tudou discord server https://discord.gg/cWXVW8mwJs", false);
        $embed->addFieldValues("tudou invite","https://discord.com/oauth2/authorize?client_id=1260317610059501570&scope=bot&permissions=8", false);
        $embed->addFieldValues("created by deplantis","created by deplantis", false);
        $embed->setColor(0xff8890);
        $message->channel->sendEmbed($embed);
    }

     
    if ($messagecontent == "tudou!generate" || $messagecontent == "tudou!picture" || $messagecontent == "tudou!imagine" || $messagecontent == "tudou!tudou" || $messagecontent == "t!generate" ||  $messagecontent == "t!imagine" || $messagecontent == "t!picture" || $messagecontent == "t!tudou") 
    {
        $Tudou->generate($discord,$message);
    }


    if (substr($messagecontent ,0, 13) == "tudou!choice ") 
    {
        $response = substr($messagecontent ,13,  strlen($messagecontent));
        
        if (is_int((int)$response))
        {
            $Tudou->choice($discord,$message, $response);
        }
        else
        {
            $embed = new Embed($discord);
            $embed->setTitle("tudou!choice");
            $embed->addFieldValues("error","invalid parameter", false);
            $embed->setColor($Tudou->config->hexcode);
            $message->channel->sendEmbed($embed);
        }
    }

    if (substr($messagecontent ,0, 15) == "tudou!specific ") 
    {
        $response = substr($messagecontent ,15,  strlen($messagecontent));
        
        if (is_int((int)$response))
        {
            $Tudou->choice($discord,$message, $response);
        }
        else
        {
            $embed = new Embed($discord);
            $embed->setTitle("tudou!specific");
            $embed->addFieldValues("error","invalid parameter", false);
            $embed->setColor($Tudou->config->hexcode);
            $message->channel->sendEmbed($embed);
        }
    }


    if ($messagecontent == "tudou!admin") 
    {
        $embed = new Embed($discord);
        $embed->setTitle("tudou!admin");
        $embed->addFieldValues("tudou!admin", "give the list of all commands of admin", false);
        $embed->addFieldValues("tudou!config", "give config information", false);
        $embed->addFieldValues("tudou!config embed (bool value, for example true or false)", "change embed bool", false);
        $embed->addFieldValues("tudou!config hex (hex code, for example 0xff88d5)", "change hex code of the embed", false);
        $embed->addFieldValues("tudou!stop", "stop the bot", false);
        $embed->setColor(0xff8890);
        $message->channel->sendEmbed($embed);
    }

    if ($messagecontent == "tudou!stop") 
    {
        $embed = new Embed($discord);
        $embed->setTitle("tudou!stop");
        $embed->addFieldValues("Tudou stop", "This discord bot will be stopped after some minutes....", false);
        $embed->setColor(0xff8890);
        $message->channel->sendEmbed($embed);

        $Tudou->flags->stopflag = 1;
    }

    if ($messagecontent  == "tudou!config") 
    {
        $embed = new Embed($discord);
        $embed->setTitle("tudou!config");
        $embed->addFieldValues("Tudou embed", $Tudou->config->embedbool ? "Tudou embed true" : "tudou embed false", false);
        $embed->addFieldValues("Tudou hex ", "tudou hex code ". $Tudou->config->hexcode, false);
        $embed->setColor(0xff8890);
        $message->channel->sendEmbed($embed);
    }


    if (substr($messagecontent ,0, 19) == "tudou!config embed ") 
    {
        $response = substr($messagecontent ,19,  strlen($messagecontent));

        if ($response == "true" || $response == "yes")
        {
            $embed = new Embed($discord);
            $embed->setTitle("tudou!config");
            $embed->addFieldValues("Tudou embed ", "settings will be applied after some minutes.... ", false);
            $embed->setColor(0xff8890);
            $message->channel->sendEmbed($embed);

            $Tudou->WriteToConfig(true, $Tudou->config->hexcode);

        }
        else if ($response == "false" || $response == "no")
        {
            $embed = new Embed($discord);
            $embed->setTitle("tudou!config");
            $embed->addFieldValues("Tudou embed ", "settings will be applied after some minutes.... ", false);
            $embed->setColor(0xff8890);
            $message->channel->sendEmbed($embed);

            $Tudou->WriteToConfig(false, $Tudou->config->hexcode);
        }
        else
        {
            $embed = new Embed($discord);
            $embed->setTitle("tudou!config");
            $embed->addFieldValues("Tudou embed ", "wrong parameter, settings cannot be applied", false);
            $embed->setColor(0xff8890);
            $message->channel->sendEmbed($embed);
        }
    }

        
    if (substr($messagecontent,0, 17) == "tudou!config hex ") 
    {
        $response = substr($messagecontent ,17, strlen($messagecontent));

        $embed = new Embed($discord);
        $embed->setTitle("tudou!config");
        $embed->addFieldValues("Tudou embed ", "settings will be applied after some minutes.... ", false);
        $embed->setColor(0xff8890);
        $message->channel->sendEmbed($embed);

        $Tudou->WriteToConfig($Tudou->config->embedbool, $response);
        
    } 

    echo "{$message->author->username}: {$message->content}". "\n";

});

$discord->run();
