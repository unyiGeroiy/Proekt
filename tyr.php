<?php


class Player
{
    private int $id;
    private static int $cnt = 0;
    public string $name;
    public bool $alive = true;
    public int $strength, $agility, $luck;

    public function __construct($name)
    {
        self::$cnt++;
        $this->id = self::$cnt;
        $this->name = $name;
        $this->strength = mt_rand(1, 100);
        $this->agility = mt_rand(1, 100);
        $this->luck = mt_rand(1, 100);
    }


    public function kill(): void{
        $this->alive = false;
    }

    public function printInfo(): void{
        echo $this->strength . " " . $this->luck;
    }

    public function aliveGetter():bool   {
        return $this->alive;
    }

    public function GetName(){
        return $this->name;
    }

}

$tom = new Player('tom');


abstract class Game{
    protected string $name;
    protected string $rules;

    public function __construct($name, $rules){
        $this->name = $name;
        $this->rules = $rules;
    }

    abstract public function play(array $players): array;

    public function nameGetter(){
        return $this->name;
    }

    public function rulesGetter(){
        return $this->rules;
    }
}

class RedLightGreenLight extends Game{
    
    public function play($players) : array{
        foreach($players as $player) {                   // Реализовать проверку на экземпляр класса Игрока
            if ($player->aliveGetter() == true) {
                $rand = mt_rand(1,100);
                if ($rand > $player->agility/2) {
                    $player->kill();
                }
                
            }
            

        }
        return $players;
    }

}

class HoneyComb extends Game{
    public function play($players) : array{
        foreach($players as $player){                          // Реализовать проверку на экземпляр класса Игрока                              
            if ($player->aliveGetter() == true) {
                $rand = mt_rand(60, 100);
                if ($rand > $player->agility+$player->luck){
                    $player->kill();
                }
            }

        }
        return $players;
    }
}

class TugOfWar extends Game{

    public function play($players) : array{
        $team1 = [];
        $team2 = [];
        $summstrength1 = 0;
        $summstrength2 = 0;
        $counter = 1;
        foreach($players as $player){
            if ($player->aliveGetter() == true){
                if ($counter%2 == 0){
                    $team1[] = $player;
                    $summstrength1 += $player->strength;
                    $counter++;
                }
                else{
                    $team2[] = $player;
                    $summstrength2 += $player->strength;
                    $counter++;
                }
            }


        }
        if ($counter == 2){
            return $players;
        }
        elseif ($summstrength1 > $summstrength2){
            foreach($team2 as $player){
                $player->kill();
            }
        }
        else{
            foreach($team1 as $player){
                $player->kill();
            }
        }
        return $players;
    }
    
}

class GlassesBridge extends Game{
    public function play($players) : array{
        $waylen = 4;
        foreach($players as $player){
            if ($player->aliveGetter() == true){
                while ($player->aliveGetter() == true && $waylen != 0){
                    $rand = mt_rand(1, 100);
                    if ($rand%2 == 0){
                        $player->kill();
                        
                    }
                    else{
                        $waylen--;
                    }
                }   
            }
            
        }   
    
        return $players;
    }
}


class SquidGame
{
    public array $players;
    public array $games;

    public function addPlayer($player){
        $this->players[] = $player;
    }

    public function addGame($game){
        $this->games[] = $game;
    }

    public function start(){
        foreach($this->games as $game){
            if ($game instanceof Game){
                $game->play($this->players);
                $playercnt = count($this->players);
                $cnt = 0;
                $arr = [];
                foreach($this->players as $player){
                    if ($player->aliveGetter() == true && $player instanceof Player){
                        $cnt++;
                        $arr[] = $player;
                    }
                }
                echo 'Название игры ==    '. $game->nameGetter(). "\n" . 'Правила игры ==   '.$game->rulesGetter(). "\n";
                echo ' ';
                echo "Выжило: $cnt из $playercnt  \n";
                echo "Выжившие игроки: ";
                echo ' '. "\n". '  ';
                foreach($arr as $player){
                    echo '  '. $player->GetName(). ',';
                }
                echo "\n";

            }
        }
    }

    public function getWinner(){
        $maxi = 0;
        $winner = null;
        foreach($this->players as $player){
            if ($player->aliveGetter() == true){
                if ($maxi < $player->strength + $player->agility + $player->luck){
                    $maxi = $player->strength + $player->agility;
                    $winner = $player;
                }
            }
        }
        return $winner;
    }

}

$names = [
      'Ким Минсу', 'Ли Джиын', 'Пак Джунхо', 'Чхве Суджин', 'Чон Хёнву',
      'Хан Соён', 'Юн Тхэён', 'Кан Миён', 'Чо Сонмин', 'Им Джихён',
      'Сон Минджун', 'Пэк Дживон', 'О Сынхо', 'Син Юджин', 'Квон Тхэхён',
      'Ан Сохи', 'Хон Джунён', 'Ю Мигён', 'Нам Донхён', 'Ко Ынджи',
      'Мун Чжэхён', 'Ян Суджин', 'Ку Тхэён', 'Сон Минджи', 'Пэ Джунхо',
      'Чо Ынён', 'Хан Донхён', 'Юн Соджин', 'Ким Тхэу', 'Ли Миён'
    ];

//
$players = [];
foreach($names as $name){
    $players[] = new Player($name);
} 

$games = [
    new RedLightGreenLight('Красный свет, зеленый свет', 'Участники должны двигаться только когда звучит "Зеленый свет". На "Красный свет" движение запрещено.'),
    new Honeycomb('Сахарный пряник', 'Нужно вырезать фигуру из медовой ириски.'),
    new GlassesBridge('Стеклянный мост', 'Нужно пройти все плиты.'),
    new TugOfWar('Перетягивание каната', ' Участники делятся на две команды.')
];


$squidGame = new SquidGame();
foreach ($players as $player) {
    $squidGame->addPlayer($player);
}
foreach ($games as $game) {
    $squidGame->addGame($game);
}


$squidGame->start();
$winner = $squidGame->getWinner();

if ($winner !== null) {
    echo 'Победитель: ' . $winner->GetName() . "\n";
} else {
    echo "Победителя нет, все игроки погибли \n";
}