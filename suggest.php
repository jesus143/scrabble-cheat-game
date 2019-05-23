<?php

// get parameter
$keyword = (! empty($_GET['keyword'])) ? $_GET['keyword'] : null ;

// set letter total points
$points = [
    [
        'points' => 1,
        'letters' => 'A,E,I,O,U,L,N,S,T,R'
    ],
    [
        'points' => 2,
        'letters' => 'D,G'
    ],
    [
        'points' => 3,
        'letters' => 'B,C,M,P'
    ],
    [
        'points' => 4,
        'letters' => 'F,H,V,W,Y'
    ],
    [
        'points' => 5,
        'letters' => 'K'
    ],
    [
        'points' => 8,
        'letters' => 'J,X'
    ],
    [
        'points' => 10,
        'letters' => 'Q,Z'
    ]
];

/**
 * Search work matched from they keyword
 *
 * @param $keyword
 * @param $points
 * @return array
 */
function searchKeyword($keyword, $points) {
    // the following line prevents the browser from parsing this as HTML.
    header('Content-Type: text/plain');

    // accepted
    $keyword = strtolower($keyword);

    // set text file name
    $fileName = 'dictionary.txt';

    $names = file($fileName);
    $foundData = [];

    // counter for the result
    $counter = 0;

    // get the matched words and total points
    foreach ($names as $index => $name) {
        $lenSubject = strlen(trim($name));
        $lenFound = 0;
        $lenKeyword = strlen($keyword);
        $indexFoundKeyword = [];
        $indexFoundSubject = [];
        $totalPints = 0;

        for($i=0; $i<$lenKeyword; $i++) {
            for($j=0; $j<$lenSubject; $j++) {
                if(! in_array($i, $indexFoundKeyword)) {
                    if(! in_array($j, $indexFoundSubject)) {
                        if ($keyword[$i] == $name[$j]) {
                            $indexFoundKeyword[] = $i;
                            $indexFoundSubject[] = $j;
                            $lenFound++;

                            $totalPints += getPoints($keyword[$i], $points);
                        }
                    }
                }
            }
        }

        // assigned the matched words and total points to an array
        if($lenSubject == $lenFound) {
            $foundData[$counter]['word'] = $name;
            $foundData[$counter]['points'] = $totalPints;
            $counter++;
        }
    }

    return $foundData;
}

/**
 * Get and calculate the total points per letter
 *
 * @param $keyword
 * @param $points
 * @return mixed
 */
function getPoints($keyword, $points) {
    $keyword = strtolower($keyword);

    foreach($points as $point) {
        $letters = strtolower($point['letters']);

        if(strpos($letters, $keyword) > -1) {
            return $point['points'];
        }
    }
}

// set parameter for the search word
$response = searchKeyword($keyword, $points);

// set json format so that frontend side can capture the result
echo json_encode($response);



