<?php
function ProfanityFilter($text) {
  // array
  $filterWords = array(
    "nigger",
    "bldn",
    "brick luke deez nuts",
    "bigot",
    "faggot",
    "cunt",
    "dickwad",
    "furfag",
    "russian pig",
    "rigging",
    "fag",
    "swine",
    "cracker",
    "squaw",
    "nigga",
    "mong",
    "cock and ball torture",
    "isaac hymer",
    "spacebuilder",
    "brick luke",
    "brick-luke",
    "brick-hill",
    "brickhill",
    "porn",
    "ejaculation",
    "cum",
    "sperm",
    "semen",
    "cowgirl",
    "blowjob",
    "doggly style",
    "pornography",
    "jacking off",
    "jerking off",
    "sex",
    "pornhub",
    "stripper",
    "prostitute",
    "xvideos",
    "e621",
    "rule34",
    ".onion",
    "wanker",
    "hitler"
  );

$filterCount = sizeof($filterWords);
 for ($i = 0; $i < $filterCount; $i++) {
  $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function($matches){return str_repeat('*', strlen($matches[0]));}, $text);
 }
 return $text;
}

// GOD FUCKING KILL ME