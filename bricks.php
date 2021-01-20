<?php

//Assignment for Mentormate dev camp performed by Deyan Mihaylov

class Brickwork {

	private $layer1 = [];
	private $layer2 = [];
	private $bricks = [];
	private $layer2model = [];
	private $n;
	private $m;
	public $result;

	function __construct($testData=[]){

		
		if($testData){
			return $this->test($testData);
		}
		
			//define the layer size
			$nm = readline("Enter N M: ");

			$t = explode(' ', $nm);

			if(sizeof($t) != 2)
				return $this->error('The program expects 2 parameters.');

			$this->n = (int)$t[0];
			$this->m = (int)$t[1];
		

		if($this->n % 2 !== 0 || $this->m % 2 !== 0 || $this->n > 100 || $this->m > 100)
			return $this->error("M and N should be a even numbers between 2 and 100.");

		if($this->n * $this->m < 8)
			return $this->error('Minimum number of bricks is 8.');

		$this->result = $this->readInputLines();
		$this->result = $this->fillLayer2();

	}

	function error($error){


		exit("-1\n".$error);

	}

	function test($testData){

		$this->n = sizeof($testData);
		$this->m = sizeof($testData[0]);

		foreach ($testData as $n => $numbers) {
			foreach ($numbers as $number) {
				$this->layer1[$n][] = $number;
			}
		}
		$this->checkLayer1();

		$this->result = $this->fillLayer2();
	}

	function readInputLines(){

		$ln = [];
		$usedBricks = [];

		for($i = 0; $i < $this->n; $i++){

			$lineNum = $i+1;

			$ln[$i] = readline("Line {$lineNum}: ");

			$line = explode(" ", $ln[$i]);

			if(sizeof($line) != $this->m)
				return $this->error('Count of numbers do not match expected M');

			foreach($line as $key => $brickNum){
				
				if(!isset($usedBricks[$brickNum]))
					$usedBricks[$brickNum] = 0;

				$usedBricks[$brickNum]++;

				$this->layer1[$i][$key] = $brickNum;
			}
		}

			//checks if all the bricks are used only twice
			foreach($usedBricks as $brickNum => $value){
				
				if($value != 2)
					$this->error('Some of the bricks are not used twice.');

			}

			return $this->checkLayer1();

	}

	function checkLayer1(){

		//Checks if all the bricks are grouped in couples
		for ($i = 0; $i < $this->n; $i++){
			for ($k = 0; $k < $this->m; $k++){


					if($i % 2 === 0 && 
					$this->layer1[$i][$k] != $this->layer1[$i+1][$k]
					&& ((isset($this->layer1[$i][$k+1]) && $this->layer1[$i][$k] != $this->layer1[$i][$k+1]))
					&& ((isset($this->layer1[$i][$k-1]) && $this->layer1[$i][$k] != $this->layer1[$i][$k-1]))
					)
					return $this->error('Some of the bricks are not in a couple.');


					//Creates an array with all bricks with current position to use them in layer 2
					if(!in_array($this->layer1[$i][$k], $this->bricks))
					$this->bricks[] = $this->layer1[$i][$k];
			}
		}

		return 'Layer 1 looks good.';
	}

	function checkForDublicatedBricks(){

		$dublicated = [];

		for ($i = 0; $i < $this->n; $i++){
			for ($k = 0; $k < $this->m; $k++){

				if($this->layer1[$i][$k] === $this->layer2[$i][$k]){

					$dublicated[$this->layer1[$i][$k]]++;

					if($dublicated[$this->layer1[$i][$k]] > 1)
					return $this->error('Dublicated brick - '.$this->layer1[$i][$k]);
				}
			}
		}
	}

	function getBrick(){

		$brick = end($this->bricks);
		array_pop($this->bricks);
		return $brick;
	}

	function fillLayer2(){

		//$this->printLayer($this->layer1);

		for ($i = 0; $i < $this->n; $i+=2){

			for ($k = 0; $k < $this->m; $k++){

				$layer1 = $this->layer1[$i];

				$this->layer2[$i][$k] = $this->getBrick();

				if(!isset($layer1[$k+1])
				|| ($layer1[$k] === $layer1[$k+1])){
					//$this->layer2model[$i][$k] = 1;
					$this->layer2[$i+1][$k] = $this->layer2[$i][$k];
				}
				else{ 
					//$this->layer2model[$i][$k] = 2;
					//$this->layer2model[$i][$k+1] = 2;
					$this->layer2[$i+1][$k] = $this->getBrick();
					$this->layer2[$i][$k+1] = $this->layer2[$i][$k];
					$this->layer2[$i+1][$k+1] = $this->layer2[$i+1][$k];

					$k++;
				}
			}
		}

			$this->printLayer($this->layer2);
			
			$this->checkForDublicatedBricks();
	}

	function printLayer($layerData){

		foreach($layerData as $n => $line){

				$line1 = '';
				$line2 = '';

			foreach($line as $pos => $m){

				$line1 .= $m.($m<10?" ":"");

				if(isset($layerData[$n+1]) && $layerData[$n+1][$pos] != $m){
					$line2 .= "--".($m>90?"-":"");
				}
				else $line2 .= "  ".($m>90?" ":"");

				if(isset($line[$pos+1]) && ($pos && $line[$pos-1] == $m) ){
					$line1 .= " *  ";
					$line2 .= " *  ";
				}
				elseif(isset($line[$pos+1]) && ($line[$pos+1] != $m)){
					$line1 .= " * ";
					$line2 .= " * ";
				}
				elseif(sizeof($line)-1!==$pos){
					$line1 .= "  ";
					$line2 .= "--";
				}

			}
				//some more styling
				if($n == sizeof($layerData)-1)
					$line2 = str_replace(" ", "-", $line2);

				if(!$n)
				echo str_replace(" ", "-", $line2)."\n";

				echo $line1."\n";
				echo $line2."\n";
		}
	}
}

$testData1 = [
	[1,1,2,2],
	[3,3,4,4]];

$testData2 = [
	[1,2,2,12,5,7,7,16],
	[1,10,10,12,5,15,15,16],
	[9,9,3,4,4,8,8,14],
	[11,11,3,13,13,6,6,14]
];

//$brickwork = new Brickwork($testData1);
$brickwork = new Brickwork();
echo $brickwork->result;

?>